<?php

use App\Helpers\SystemSettingHelper;

if (!function_exists('setting')) {
    function setting($key, $default = null) {
        return SystemSettingHelper::get($key, $default);
    }
}

if (!function_exists('setting_formatted')) {
    function setting_formatted($key, $default = null) {
        return SystemSettingHelper::getFormatted($key, $default);
    }
}

if (!function_exists('format_currency')) {
    function format_currency($amount) {
        return SystemSettingHelper::formatCurrency($amount);
    }
}

if (!function_exists('company_info')) {
    function company_info($key = null) {
        $info = SystemSettingHelper::getCompanyInfo();
        return $key ? ($info[$key] ?? null) : $info;
    }
}

if (!function_exists('financial_settings')) {
    function financial_settings() {
        return SystemSettingHelper::getFinancialSettings();
    }
}

if (!function_exists('journal_settings')) {
    function journal_settings() {
        return SystemSettingHelper::getJournalSettings();
    }
}

if (!function_exists('clear_system_cache')) {
    function clear_system_cache() {
        return SystemSettingHelper::clearSystemCache();
    }
}

if (!function_exists('terbilang')) {
    function terbilang($angka) {
        return \App\Helpers\TerbilangHelper::convert($angka);
    }
}

if (!function_exists('terbilang_currency')) {
    function terbilang_currency($amount, $currency = 'rupiah') {
        return \App\Helpers\TerbilangHelper::currency($amount, $currency);
    }
}

if (!function_exists('terbilang_official')) {
    function terbilang_official($amount) {
        return \App\Helpers\TerbilangHelper::official($amount);
    }
}