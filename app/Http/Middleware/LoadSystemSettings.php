<?php

namespace App\Http\Middleware;

use App\Helpers\SystemSettingHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class LoadSystemSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Share system settings with all views
        View::share([
            'companyInfo' => SystemSettingHelper::getCompanyInfo(),
            'financialSettings' => SystemSettingHelper::getFinancialSettings(),
            'journalSettings' => SystemSettingHelper::getJournalSettings(),
            'systemSettings' => [
                'timezone' => SystemSettingHelper::get('system_timezone', 'Asia/Jakarta'),
                'date_format' => SystemSettingHelper::get('date_format', 'd/m/Y'),
                'time_format' => SystemSettingHelper::get('time_format', 'H:i'),
                'records_per_page' => SystemSettingHelper::get('records_per_page', 25),
            ]
        ]);

        return $next($request);
    }
}
