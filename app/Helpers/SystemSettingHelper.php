<?php

namespace App\Helpers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SystemSettingHelper
{
    /**
     * Get system setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = SystemSetting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Get formatted system setting value by key
     */
    public static function getFormatted($key, $default = null)
    {
        return Cache::remember("system_setting_formatted_{$key}", 3600, function () use ($key, $default) {
            $setting = SystemSetting::where('key', $key)->first();
            return $setting ? $setting->formatted_value : $default;
        });
    }

    /**
     * Set system setting value
     */
    public static function set($key, $value, $type = 'text', $group = 'general', $description = null)
    {
        $result = SystemSetting::set($key, $value, $type, $group, $description);
        
        // Clear cache
        Cache::forget("system_setting_{$key}");
        Cache::forget("system_setting_formatted_{$key}");
        
        return $result;
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return Cache::remember("system_settings_group_{$group}", 3600, function () use ($group) {
            return SystemSetting::getByGroup($group);
        });
    }

    /**
     * Get company information
     */
    public static function getCompanyInfo()
    {
        return [
            'name' => self::get('company_name', 'BUMDES'),
            'logo' => self::getFormatted('company_logo'),
            'village_name' => self::get('village_name'),
            'village_address' => self::get('village_address'),
            'village_phone' => self::get('village_phone'),
            'village_email' => self::get('village_email'),
            'director_name' => self::get('director_name'),
            'director_nip' => self::get('director_nip'),
            // Alias untuk kompatibilitas dengan view
            'address' => self::get('village_address'),
            'phone' => self::get('village_phone'),
            'email' => self::get('village_email'),
        ];
    }

    /**
     * Get financial settings
     */
    public static function getFinancialSettings()
    {
        return [
            'currency' => self::get('default_currency', 'IDR'),
            'currency_symbol' => self::get('currency_symbol', 'Rp'),
            'decimal_places' => (int) self::get('decimal_places', 2),
            'thousand_separator' => self::get('thousand_separator', '.'),
            'decimal_separator' => self::get('decimal_separator', ','),
            'fiscal_year_start' => self::get('fiscal_year_start', '01-01'),
        ];
    }

    /**
     * Get journal settings
     */
    public static function getJournalSettings()
    {
        return [
            'auto_numbering' => (bool) self::get('auto_journal_numbering', true),
            'prefix' => self::get('journal_prefix', 'JRN'),
            'require_approval' => (bool) self::get('require_journal_approval', true),
            'allow_backdated' => (bool) self::get('allow_backdated_entries', false),
        ];
    }

    /**
     * Format currency value
     */
    public static function formatCurrency($amount)
    {
        $settings = self::getFinancialSettings();
        
        return $settings['currency_symbol'] . ' ' . number_format(
            $amount,
            $settings['decimal_places'],
            $settings['decimal_separator'],
            $settings['thousand_separator']
        );
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $keys = SystemSetting::pluck('key');
        
        foreach ($keys as $key) {
            Cache::forget("system_setting_{$key}");
            Cache::forget("system_setting_formatted_{$key}");
        }
        
        // Clear group caches
        $groups = SystemSetting::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("system_settings_group_{$group}");
        }
    }

    /**
     * Clear all system cache (settings cache + Laravel optimize:clear)
     */
    public static function clearSystemCache()
    {
        // Clear settings cache first
        self::clearCache();
        
        // Run Laravel optimize:clear command
        Artisan::call('optimize:clear');
        
        return true;
    }
}