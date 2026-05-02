<?php

if (!function_exists('currency')) {
    /**
     * Format currency with primary and optional secondary
     */
    function currency(float $amount, bool $showBoth = true): string
    {
        return \App\Helpers\CurrencyHelper::format($amount, $showBoth);
    }
}

if (!function_exists('primary_currency')) {
    /**
     * Get primary currency symbol
     */
    function primary_currency(): string
    {
        return \App\Helpers\CurrencyHelper::primary();
    }
}

if (!function_exists('secondary_currency')) {
    /**
     * Get secondary currency symbol
     */
    function secondary_currency(): ?string
    {
        return \App\Helpers\CurrencyHelper::secondary();
    }
}
