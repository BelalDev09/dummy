<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'avatar',
        'name',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'otp',
        'otp_created_at',
        'otp_expires_at',
        'reset_token',
        'role',
        'status',
        'tax',
        'ssn_ein',
        'city',
        'zip_code',
        'document',
        'privacy_policy',
        'is_admin',
        'stripe_account_id',
        'stripe_customer_id',
        'stripe_subscription_id',
        'payment_method',
        'payment_method_id',
        'is_card',
        'is_bank',
        'auto_purchase',
        'provider',
        'provider_id',
        'provider_refresh_token',
        'term_and_conditions'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_created_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'gamls_access' => 'boolean',
            'privacy_policy' => 'boolean',
            'onboard_complete' => 'boolean',
            'auto_purchase' => 'boolean',
            'trial_ends_at' => 'datetime',
            'docusign_access_token' => 'string',
            'docusign_refresh_token' => 'string',
            'docusign_token_expires_at' => 'datetime',
            'stripe_account_id' => 'string',
            'stripe_customer_id' => 'string',
            'payment_method_id' => 'string',
            'is_card' => 'boolean',
            'is_bank' => 'boolean',
            'term_and_conditions' => 'bollean'
        ];
    }

    public function ownerNotifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    // relations
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function currentMembershipPlan()
    {
        return $this->activeAgentSubscription?->membershipPlan;
    }

    public function getCurrentMembershipPlanAttribute()
    {
        return $this->activeAgentSubscription?->membershipPlan;
    }

    /**
     * Get current plan name (accessor)
     */
    public function getCurrentPlanNameAttribute()
    {
        return $this->current_membership_plan?->name ?? 'No Plan';
    }

    public function scopeAgents($query)
    {
        return $query->where('role', 'agent');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAvatarAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function getDocumentAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }



    public function getPhotoIdPathAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }

    public function getDocumentPathAttribute($value): string|null
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if (request()->is('api/*') && !empty($value)) {
            return url($value);
        }
        return $value;
    }
}
