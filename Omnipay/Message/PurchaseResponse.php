<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return !empty($this->data['PaymentURL']) && ($this->data['Success'] ?? false) === true;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->data['PaymentURL'] ?? null;
    }

    public function getRedirectMethod(): string
    {
        return 'GET';
    }

    public function getRedirectData(): array
    {
        return [];
    }

    public function getMessage(): ?string
    {
        return $this->data['Message'] ?? $this->data['Details'] ?? null;
    }

    public function getCode(): ?string
    {
        return isset($this->data['ErrorCode']) ? (string) $this->data['ErrorCode'] : null;
    }

    public function getTransactionReference(): ?string
    {
        return isset($this->data['PaymentId']) ? (string) $this->data['PaymentId'] : null;
    }
}
