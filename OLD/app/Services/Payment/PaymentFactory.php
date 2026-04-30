<?php

namespace App\Services\Payment;

use App\Services\Payment\Gateways\StripeService;
use App\Services\Payment\Gateways\PaypalService;
use App\Services\Payment\Gateways\ChamCashService;
use App\Services\Payment\Gateways\ChamCashPersonalService;
use App\Services\Payment\Gateways\SyriatelCashService;

class PaymentFactory
{
    public static function make($gateway)
    {
        switch ($gateway) {
            case 'stripe':
                return new StripeService();
            case 'paypal':
                return new PaypalService();
            case 'cham_cash':
                return new ChamCashService();
            case 'cham_cash_personal':
                return new ChamCashPersonalService();
            case 'syriatel_cash':
                return new SyriatelCashService();
            default:
                throw new \Exception("Unsupported Payment Gateway: $gateway");
        }
    }
}
