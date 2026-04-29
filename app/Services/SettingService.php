<?php

namespace App\Services;

use App\Helper\Helper;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingService extends Service
{
    public function adminSettingPage()
    {
        $data['setting'] = SystemSetting::firstOrNew([]);

        return view('backend.layout.setting.admin-setting')->with($data);
    }

    public function adminSettingUpdate($title, $logo, $favicon, $tag, $code, $phone, $email, $copyright)
    {
        try {
            DB::beginTransaction();

            $setting = SystemSetting::firstOrNew([]);

            $setting->system_title   = Str::title($title);
            $setting->tag_line       = $tag;
            $setting->phone_code     = $code;
            $setting->phone_number   = $phone;
            $setting->email          = $email;
            $setting->copyright_text = $copyright;

            if ($logo) {
              
                Helper::deleteFile($setting->getRawLogo());
                $setting->logo = Helper::fileUpload($logo, 'systems/logo', 'logo');
            }

            if ($favicon) {
                Helper::deleteFile($setting->getRawFavicon());
                $setting->favicon = Helper::fileUpload($favicon, 'systems/favicon', 'favicon');
            }

            $setting->save();
            DB::commit();

            return redirect()->back()->with('message', 'Updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
