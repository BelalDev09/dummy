<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\apiresponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    use apiresponse;

    /* ---------------- REGISTER ---------------- */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 'active',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /* ---------------- LOGIN ---------------- */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            return response()->json(['message' => 'Account inactive .Please contact the administrator.'], 403);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    /* ---------------- FORGET PASSWORD ---------------- */

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // OTP generate
        $otp = rand(1000, 9999);

        // Save in DB
        $user->otp = $otp;
        $user->otp_created_at = now();
        $user->save();

        // Send Email
        Mail::raw("Your OTP code is: {$otp}", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset OTP');
        });

        return response()->json([
            'message' => 'OTP sent successfully to email',
        ]);
    }

    /* ---------------- VERIFY OTP ---------------- */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->otp || $user->otp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if ($user->otp_created_at && now()->gt(Carbon::parse($user->otp_created_at)->addMinutes(15))) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        //  generate reset token
        $resetToken = Str::random(60);

        $user->reset_token = $resetToken;
        $user->otp = null;
        $user->otp_created_at = null;
        $user->save();

        return response()->json([
            'message' => 'OTP verified successfully',
            'reset_token' => $resetToken,
        ]);
    }

    /* ---------------- RESET PASSWORD ---------------- */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('reset_token', $request->reset_token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        return response()->json([
            'message' => 'Password reset successful',
        ]);
    }

    /* ---------------- RESEND OTP ---------------- */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->otp = rand(1000, 9999);
        $user->otp_created_at = now();
        $user->save();

        return response()->json([
            'message' => 'OTP resent successfully',
        ]);
    }
    /**
     * social login
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'provider' => 'required|in:google,apple',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $provider = $request->provider;
            $token = $request->token;

            $socialiteUser = Socialite::driver($provider)
                ->stateless()
                ->userFromToken($token);

            if (! $socialiteUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid social token.',
                ], 422);
            }

            $user = User::where('provider', $provider)
                ->where('provider_id', $socialiteUser->getId())
                ->first();

            $isNewUser = false;

            if (! $user && $socialiteUser->getEmail()) {
                $user = User::where('email', $socialiteUser->getEmail())->first();
            }

            if (! $user) {
                $user = User::create([
                    'name' => $socialiteUser->getName() ?? ucfirst($provider) . ' User',
                    'email' => $socialiteUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'password' => Hash::make(Str::random(16)),
                    'role' => 'User',
                ]);

                $isNewUser = true;
            } else {
                if (! $user->provider_id) {
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialiteUser->getId(),
                    ]);
                }
            }

            $jwt = auth('api')->login($user);

            return response()->json([
                'success' => true,
                'message' => $isNewUser
                    ? 'User registered successfully.'
                    : 'User logged in successfully.',
                'data' => [
                    'user' => $user,
                    'token' => $jwt,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* ---------------- ME ---------------- */
    public function me()
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }

    /* ---------------- LOGOUT ---------------- */
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
}
