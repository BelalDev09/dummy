<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'system_title',
        'system_short_title',
        'logo',
        'minilogo',
        'favicon',
        'company_name',
        'tag_line',
        'phone_code',
        'phone_number',
        'whatsapp',
        'email',
        'time_zone',
        'language',
        'copyright_text',
        'admin_title',
        'admin_short_title',
        'admin_logo',
        'admin_mini_logo',
        'admin_favicon',
        'admin_copyright_text',
    ];

    private const DEFAULT_IMAGE = 'Backend/assets/images/no-image.png';


    public function getLogoAttribute($value): string
    {
        return $value ?: self::DEFAULT_IMAGE;
    }

    public function getFaviconAttribute($value): string
    {
        return $value ?: self::DEFAULT_IMAGE;
    }

    public function getAdminLogoAttribute($value): string
    {
        return $value ?: self::DEFAULT_IMAGE;
    }

    public function getAdminFaviconAttribute($value): string
    {
        return $value ?: self::DEFAULT_IMAGE;
    }



    public function getRawLogo(): ?string
    {
        $val = $this->getOriginal('logo');
        return ($val && $val !== self::DEFAULT_IMAGE) ? $val : null;
    }

    public function getRawFavicon(): ?string
    {
        $val = $this->getOriginal('favicon');
        return ($val && $val !== self::DEFAULT_IMAGE) ? $val : null;
    }

    public function getRawAdminLogo(): ?string
    {
        $val = $this->getOriginal('admin_logo');
        return ($val && $val !== self::DEFAULT_IMAGE) ? $val : null;
    }

    public function getRawAdminFavicon(): ?string
    {
        $val = $this->getOriginal('admin_favicon');
        return ($val && $val !== self::DEFAULT_IMAGE) ? $val : null;
    }

    //  Helper

    public static function adminEmail(): string
    {
        return self::first()?->email ?? 'admin@admin.com';
    }
}
