<?php

namespace Flute\Modules\TBankPayment\Providers;

use Flute\Core\Modules\Payments\Events\RegisterPaymentFactoriesEvent;
use Flute\Core\Modules\Payments\Factories\PaymentDriverFactory;
use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\TBankPayment\Listeners\TBankRegisterListener;
use Flute\Modules\TBankPayment\Omnipay\TBankDriver;

class TBankPaymentProvider extends ModuleServiceProvider
{
    public function boot(\DI\Container $container): void
    {
        $this->bootstrapModule();

        $this->loadViews('Resources/views', 'flute-tbank');

        app(PaymentDriverFactory::class)->register('TBank', TBankDriver::class);

        events()->addDeferredListener(RegisterPaymentFactoriesEvent::NAME, [
            TBankRegisterListener::class,
            'registerTBank',
        ]);
    }

    public function register(\DI\Container $container): void
    {
    }
}
