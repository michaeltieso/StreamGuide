<?php

use App\Models\SiteSetting;

if (!function_exists('settings')) {
    /**
     * Get a setting value from the database
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function settings($key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }
}
