<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
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
        // Redirect authenticated users to home instead of dashboard for guest routes
        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(function ($request) {
            return route('home');
        });

        // Force HTTPS only for real production hosts, not localhost / artisan serve.
        $host = app()->runningInConsole() ? 'localhost' : request()->getHost();
        $isLocalHost = in_array($host, ['127.0.0.1', 'localhost'], true);

        if ((app()->environment('production') || app()->isProduction()) && ! $isLocalHost) {
            URL::forceScheme('https');
        }

        // Always share site_settings as an empty array by default
        View::share('site_settings', []);

        try {
            if (Schema::hasTable('settings')) {
                $settings = Cache::rememberForever('site_settings', function () {
                    return Setting::pluck('value', 'key')->toArray();
                });
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
                if (!empty($settings['google_client_id'])) {
                    config([
                        'services.google.client_id' => $settings['google_client_id'],
                        'services.google.client_secret' => $settings['google_client_secret'],
                        'services.google.redirect' => url('/auth/google/callback'),
                    ]);
                }
                if (!empty($settings['facebook_client_id'])) {
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

        // Performance Optimization: Prevent N+1 query issues
        Model::preventLazyLoading(!app()->isProduction());

        // Global variables for admin navigation notifications
        View::composer(['components.navbar', 'admin.dashboard', 'layouts.admin'], function ($view) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                $adminNotifications = Cache::remember('admin_notifications', 60, function () {
                    return [
                        'new_feedbacks' => \App\Models\Feedback::where('status', 'new')->count(),
                        'unread_chats'  => \App\Models\ChatbotMessage::where('is_read', false)->where('sender', 'user')->count(),
                        'new_callbacks' => \App\Models\CallbackRequest::where('status', 'new')->count(),
                        'pending_resets' => \Illuminate\Support\Facades\DB::table('password_reset_tokens')->count(),
                    ];
                });
                $adminNotifications['total_unread'] = $adminNotifications['new_feedbacks'] + $adminNotifications['unread_chats'] + $adminNotifications['new_callbacks'] + $adminNotifications['pending_resets'];
                $view->with('adminNotifications', $adminNotifications);
            }
        });

        // Share database-backed location data globally with all views
        View::composer('*', function ($view) {
            $locationData = Cache::remember('indian_location_data', 300, function () {
                if (!Schema::hasTable('states')) {
                    return [
                        'states' => [],
                        'districts' => [],
                        'allDistricts' => [],
                        'districtToState' => [],
                        'localities' => [],
                        'localitiesByState' => [],
                    ];
                }

                $states = \App\Models\State::orderBy('name')->get();
                $districts = \App\Models\District::with('state')->orderBy('name')->get();
                $localities = \App\Models\Locality::with('district.state')->orderBy('name')->get();

                $statesMap = [];
                foreach ($states as $s) {
                    $statesMap[$s->code] = $s->name;
                }

                $districtsMap = [];
                $allDistricts = [];
                $districtToStateMap = [];

                foreach ($districts as $d) {
                    $dSlug = str_replace(' ', '-', strtolower($d->name));
                    $stateCode = $d->state ? $d->state->code : '';
                    $stateName = $d->state ? $d->state->name : '';

                    if ($d->state) {
                        if (!isset($districtsMap[$stateCode])) {
                            $districtsMap[$stateCode] = [];
                        }
                        if (!in_array($d->name, $districtsMap[$stateCode])) {
                            $districtsMap[$stateCode][] = $d->name;
                        }

                        $districtsMap[strtoupper($stateCode)] = $districtsMap[$stateCode];
                        $districtsMap[strtolower($stateCode)] = $districtsMap[$stateCode];
                        $districtsMap[$stateName] = $districtsMap[$stateCode];
                        $districtsMap[strtolower($stateName)] = $districtsMap[$stateCode];
                        $districtsMap[(string)$d->state->id] = $districtsMap[$stateCode];

                        $districtToStateMap[$dSlug] = $stateCode;
                        $districtToStateMap[strtolower($d->name)] = $stateCode;
                        $districtToStateMap[$d->name] = $stateCode;
                    }

                    $allDistricts[] = [
                        'name' => $d->name,
                        'slug' => $dSlug,
                        'state_code' => $stateCode,
                        'state_name' => $stateName,
                    ];
                }

                $localitiesMap = [];
                $localitiesByStateMap = [];

                foreach ($localities as $l) {
                    if ($l->district) {
                        $dSlug = str_replace(' ', '-', strtolower($l->district->name));
                        $dNameLower = strtolower($l->district->name);
                        $dName = $l->district->name;

                        // Map by District
                        if (!isset($localitiesMap[$dSlug])) $localitiesMap[$dSlug] = [];
                        if (!in_array($l->name, $localitiesMap[$dSlug])) {
                            $localitiesMap[$dSlug][] = $l->name;
                        }
                        $localitiesMap[$dNameLower] = $localitiesMap[$dSlug];
                        $localitiesMap[$dName] = $localitiesMap[$dSlug];
                        $localitiesMap[(string)$l->district->id] = $localitiesMap[$dSlug];

                        // Map by State
                        if ($l->district->state) {
                            $sCode = $l->district->state->code;
                            $sName = $l->district->state->name;

                            if (!isset($localitiesByStateMap[$sCode])) $localitiesByStateMap[$sCode] = [];
                            if (!in_array($l->name, $localitiesByStateMap[$sCode])) {
                                $localitiesByStateMap[$sCode][] = $l->name;
                            }
                            $localitiesByStateMap[strtoupper($sCode)] = $localitiesByStateMap[$sCode];
                            $localitiesByStateMap[strtolower($sCode)] = $localitiesByStateMap[$sCode];
                            $localitiesByStateMap[$sName] = $localitiesByStateMap[$sCode];
                            $localitiesByStateMap[strtolower($sName)] = $localitiesByStateMap[$sCode];
                        }
                    }
                }

                return [
                    'states' => $statesMap,
                    'districts' => $districtsMap,
                    'allDistricts' => $allDistricts,
                    'districtToState' => $districtToStateMap,
                    'localities' => $localitiesMap,
                    'localitiesByState' => $localitiesByStateMap,
                ];
            });

            $view->with('locationData', $locationData);
            $view->with('globalAllDistricts', $locationData['allDistricts'] ?? []);
            $view->with('globalAllStates', $locationData['states'] ?? []);
        });
    }
}
