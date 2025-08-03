<?php

namespace App\Providers;

use App\Helpers\SystemSettingHelper;
use Illuminate\Support\ServiceProvider;

class SystemSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Helper functions are now loaded via composer autoload files
        // See app/helpers.php and composer.json autoload.files section
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Configure AdminLTE with system settings
        $this->configureAdminLTE();
    }

    /**
     * Configure AdminLTE with system settings
     */
    private function configureAdminLTE(): void
    {
        try {
            $companyInfo = SystemSettingHelper::getCompanyInfo();
            
            // Update AdminLTE configuration
            config([
                'adminlte.title' => $companyInfo['name'] ?? 'BUMDES',
                'adminlte.logo' => '<b>' . ($companyInfo['name'] ?? 'BUMDES') . '</b>',
                'adminlte.logo_img_alt' => $companyInfo['name'] ?? 'BUMDES Logo',
            ]);

            // Set company logo if available
            if (!empty($companyInfo['logo'])) {
                config([
                    'adminlte.logo_img' => $companyInfo['logo'],
                    'adminlte.auth_logo.img.path' => $companyInfo['logo'],
                    'adminlte.preloader.img.path' => $companyInfo['logo'],
                ]);
            }

        } catch (\Exception $e) {
            // Fallback to default values if database is not available
            // This prevents errors during migration or when database is not set up
        }
    }
}
