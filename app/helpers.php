<?php

use App\Models\Setting;

if (! function_exists('site_settings')) {
    if (! function_exists('seo_settings')) {
        /**
         * Get the latest Site settings.
         */
        function site_settings(): ?Setting
        {
            return Setting::getLatest();
        }
    }
}
