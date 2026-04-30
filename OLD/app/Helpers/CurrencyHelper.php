<?php

namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    /**
     * Get primary currency
     */
    public static function primary(): string
    {
        return Setting::getValue('currency', 'ر.س');
    }

    /**
     * Get secondary currency
     */
    public static function secondary(): ?string
    {
        return Setting::getValue('secondary_currency');
    }

    /**
     * Get exchange rate
     */
    public static function exchangeRate(): float
    {
        return (float) Setting::getValue('exchange_rate', 1);
    }

    /**
     * Format price with primary currency
     */
    public static function format(float $amount, bool $showBoth = true): string
    {
        $primary = self::primary();
        $formatted = number_format($amount, 2) . ' ' . $primary;

        if ($showBoth && self::secondary() && self::exchangeRate() > 0) {
            $converted = $amount * self::exchangeRate();
            $formatted .= ' (' . number_format($converted, 2) . ' ' . self::secondary() . ')';
        }

        return $formatted;
    }

    /**
     * Convert amount to secondary currency
     */
    public static function convert(float $amount): float
    {
        return $amount * self::exchangeRate();
    }
}
