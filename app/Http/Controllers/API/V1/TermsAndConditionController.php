<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use App\Models\FAQ;
use App\Models\SocialSetting;
use App\Traits\apiresponse;
use Illuminate\Http\JsonResponse;

class TermsAndConditionController extends Controller
{
    use apiresponse;
    public function terms(): JsonResponse
    {
        try {

            $page = DynamicPage::where('status', 'active')
                ->first();

            if (!$page) {
                return $this->error('Terms & Conditions not found', 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Terms & Conditions fetched successfully',
                'data' => [
                    'title' => $page->page_title,
                    'content' => $page->page_content,
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }


    // FAQ API
    public function faqs(): JsonResponse
    {
        try {

            $faqs = FAQ::where('status', 'active')
                ->latest()
                ->get(['id', 'que', 'ans']);

            return response()->json([
                'success' => true,
                'message' => 'FAQ list fetched successfully',
                'data' => $faqs
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
    // social method
    public function socialicon(): JsonResponse
    {
        try {

            $social = SocialSetting::where('status', 'active')
                ->get();


            return response()->json([
                'success' => true,
                'message' => 'Social list fetched successfully',
                'data' => $social
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}
