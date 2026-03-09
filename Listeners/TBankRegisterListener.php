<?php

namespace Flute\Modules\TBankPayment\Listeners;

class TBankRegisterListener
{
    public static function registerTBank()
    {
        app()->getLoader()->addPsr4('Omnipay\\TBank\\', module_path('TBankPayment', 'Omnipay/'));
        app()->getLoader()->register();
    }
}
