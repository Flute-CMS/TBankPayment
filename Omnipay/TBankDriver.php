<?php

namespace Flute\Modules\TBankPayment\Omnipay;

use Flute\Core\Modules\Payments\Drivers\AbstractOmnipayDriver;

class TBankDriver extends AbstractOmnipayDriver
{
    public ?string $adapter = 'TBank';

    public ?string $name = 'T-Bank';

    public ?string $settingsView = 'flute-tbank::settings';

    public function getValidationRules(): array
    {
        return [
            'settings__terminalKey' => ['required', 'string', 'max-str-len:255'],
            'settings__password' => ['required', 'string', 'max-str-len:255'],
            'settings__testMode' => ['required', 'boolean'],
        ];
    }
}
