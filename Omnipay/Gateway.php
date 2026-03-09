<?php

namespace Omnipay\TBank;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName(): string
    {
        return 'TBank';
    }

    public function getDefaultParameters(): array
    {
        return [
            'terminalKey' => '',
            'password' => '',
            'testMode' => false,
        ];
    }

    public function getTerminalKey(): string
    {
        return $this->getParameter('terminalKey') ?? '';
    }

    public function setTerminalKey(string $value): self
    {
        return $this->setParameter('terminalKey', $value);
    }

    public function getPassword(): string
    {
        return $this->getParameter('password') ?? '';
    }

    public function setPassword(string $value): self
    {
        return $this->setParameter('password', $value);
    }

    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\TBank\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\TBank\Message\CompletePurchaseRequest', $parameters);
    }
}
