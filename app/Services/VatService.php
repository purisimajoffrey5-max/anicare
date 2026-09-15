<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class VatService
{
    /**
     * Return the single VAT configuration controlled by Admin.
     *
     * The value stored in vat_settings.rate is a percentage:
     * 0.30 = 0.3%, 0.50 = 0.5%, 12.00 = 12%.
     */
    public static function settings(): object
    {
        $settings = DB::table('vat_settings')->first();

        return $settings ?: (object) [
            'enabled' => false,
            'rate' => 12.0000,
        ];
    }

    public static function enabled(): bool
    {
        return (bool) self::settings()->enabled;
    }

    public static function rate(): float
    {
        return (float) self::settings()->rate;
    }

    public static function amount(float $baseAmount): float
    {
        if (!self::enabled()) {
            return 0.0;
        }

        return round($baseAmount * (self::rate() / 100), 2);
    }

    /**
     * Break an existing customer total into VATable Sales + VAT.
     *
     * VAT is treated as inclusive: changing the Admin rate changes only
     * the breakdown, not the already-calculated customer total.
     */
    public static function breakdown(float $totalAmount): array
    {
        $totalAmount = round(max(0, $totalAmount), 2);
        $enabled = self::enabled();
        $rate = self::rate();

        if (!$enabled || $rate <= 0) {
            return [
                'enabled' => $enabled,
                'rate' => $rate,
                'vatable_sales' => $totalAmount,
                'vat' => 0.00,
                'total_sales' => $totalAmount,
            ];
        }

        $vatableSales = round($totalAmount / (1 + ($rate / 100)), 2);
        $vatAmount = round($totalAmount - $vatableSales, 2);

        return [
            'enabled' => true,
            'rate' => $rate,
            'vatable_sales' => $vatableSales,
            'vat' => $vatAmount,
            'total_sales' => $totalAmount,
        ];
    }
}
