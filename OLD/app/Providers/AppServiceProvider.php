<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Custom Verify Email Notification
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('تأكيد عنوان البريد الإلكتروني')
                ->greeting('مرحباً بك في مدى كيو!')
                ->line('الرجاء النقر على الزر أدناه لتأكيد عنوان بريدك الإلكتروني.')
                ->action('تأكيد البريد الإلكتروني', $url)
                ->line('إذا لم تقم بإنشاء حساب، فلا داعي لاتخاذ أي إجراء آخر.')
                ->salutation('تحياتنا،' . "\n" . 'فريق مدى كيو');
        });

        // Share settings with all views
        view()->composer('*', function ($view) {
            // Cache settings to avoid query on every partial
            static $currency;
            static $companyName;
            
            if (!$currency) {
                $currency = \App\Models\Setting::getValue('currency', 'ل.س');
                $companyName = \App\Models\Setting::getValue('company_name', 'Madaaq');
            }
            
            $view->with('currency', $currency)
                 ->with('companyName', $companyName);
        });
        
        // Money Formatter Directive
        \Illuminate\Support\Facades\Blade::directive('money', function ($expression) {
            return "<?php 
                \$val = floatval($expression);
                \$curr = \$currency ?? '$';
                
                if(\$curr === '$' || \$curr === 'USD' || \$curr === '€' || \$curr === '£') {
                    echo \$curr . ' ' . number_format(\$val, 2);
                } elseif(\$curr === 'ل.س.ج') {
                    echo number_format(\$val, 2) . ' ل.س.ج';
                } elseif (\$curr === 'ل.س') {
                    echo number_format(\$val, 0) . ' ل.س';
                } else {
                    // RTL currencies or others
                    echo number_format(\$val, 2) . ' ' . \$curr;
                }
            ?>";
        });
    }
}
