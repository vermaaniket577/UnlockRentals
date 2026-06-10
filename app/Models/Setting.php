<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value. Reads from the in-memory settings cache first.
     */
    public static function get($key, $default = null)
    {
        // Use the cached settings array loaded at boot time
        $settings = Cache::get('site_settings', []);
        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        // Fallback: direct DB query (cold cache only)
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function paymentGateways(): array
    {
        $gateways = json_decode((string) self::get('payment_gateways', '[]'), true);

        if (!is_array($gateways) || empty($gateways)) {
            $gateways = self::legacyPaymentGateway();
        }

        return collect($gateways)
            ->filter(fn ($gateway) => is_array($gateway) && filled($gateway['name'] ?? null))
            ->map(function ($gateway) {
                $gateway['id'] = $gateway['id'] ?? Str::slug($gateway['name'] ?? 'gateway') . '-' . Str::random(6);
                $gateway['type'] = $gateway['type'] ?? 'manual';
                $gateway['enabled'] = (string) ($gateway['enabled'] ?? '1');
                $gateway['reference_label'] = $gateway['reference_label'] ?? 'Transaction ID / UTR Number';
                return $gateway;
            })
            ->values()
            ->all();
    }

    public static function activePaymentGateway(): ?array
    {
        $gateways = self::paymentGateways();
        $activeId = self::get('active_payment_gateway_id');

        $active = collect($gateways)->first(fn ($gateway) => ($gateway['id'] ?? null) === $activeId);

        if (!$active) {
            $active = collect($gateways)->first(fn ($gateway) => ($gateway['enabled'] ?? '1') === '1');
        }

        return $active && ($active['enabled'] ?? '1') === '1' ? $active : null;
    }

    private static function legacyPaymentGateway(): array
    {
        $name = self::get('payment_gateway_name', 'UPI Payment');
        $razorpayKeyId = self::get('razorpay_key_id', config('services.razorpay.key_id'));
        $razorpayKeySecret = self::get('razorpay_key_secret', config('services.razorpay.key_secret'));

        $gateways = [];

        if ($razorpayKeyId || $razorpayKeySecret) {
            $gateways[] = [
                'id' => 'razorpay-default',
                'name' => 'Razorpay',
                'type' => 'razorpay',
                'enabled' => '1',
                'key_id' => $razorpayKeyId,
                'key_secret' => $razorpayKeySecret,
                'reference_label' => 'Razorpay Payment ID',
                'instructions' => 'Pay securely using UPI, card, net banking, or wallet through Razorpay.',
            ];
        }

        $gateways[] = [
            'id' => 'manual-default',
            'name' => $name,
            'type' => 'manual',
            'enabled' => '1',
            'account_name' => self::get('payment_gateway_account_name', ''),
            'identifier' => self::get('payment_gateway_identifier', ''),
            'payment_link' => self::get('payment_gateway_link', ''),
            'qr_url' => self::get('payment_gateway_qr_url', ''),
            'reference_label' => self::get('payment_reference_label', 'Transaction ID / UTR Number'),
            'instructions' => self::get('payment_gateway_instructions', ''),
        ];

        return $gateways;
    }
}
