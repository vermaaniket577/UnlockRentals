<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Always share site_settings as an empty array by default
        View::share('site_settings', []);

        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::pluck('value', 'key')->toArray();
                View::share('site_settings', $settings);

                // Dynamically override mail config
                if (isset($settings['mail_host']) && !empty($settings['mail_host'])) {
                    config([
                        'mail.mailers.smtp.host'       => $settings['mail_host'],
                        'mail.mailers.smtp.port'       => $settings['mail_port'] ?? 587,
                        'mail.mailers.smtp.username'   => $settings['mail_username'] ?? '',
                        'mail.mailers.smtp.password'   => $settings['mail_password'] ?? '',
                        'mail.mailers.smtp.encryption' => $settings['mail_encryption'] === 'none' ? null : ($settings['mail_encryption'] ?? 'tls'),
                        'mail.from.address'            => $settings['mail_from_address'] ?? ($settings['mail_username'] ?? config('mail.from.address')),
                        'mail.from.name'               => config('app.name'),
                        'mail.default'                 => 'smtp',
                    ]);
                }

                // Dynamically override social configs
                if (isset($settings['google_client_id'])) {
                    config([
                        'services.google.client_id' => $settings['google_client_id'],
                        'services.google.client_secret' => $settings['google_client_secret'],
                        'services.google.redirect' => url('/auth/google/callback'),
                    ]);
                }
                if (isset($settings['facebook_client_id'])) {
                    config([
                        'services.facebook.client_id' => $settings['facebook_client_id'],
                        'services.facebook.client_secret' => $settings['facebook_client_secret'],
                        'services.facebook.redirect' => url('/auth/facebook/callback'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if table/DB not ready (e.g. during migration)
        }
    }
}
