<?php

namespace Omnipay\TBank\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class CompletePurchaseResponse extends AbstractResponse
{
    protected $request;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;

        if (empty($this->data['Token'])) {
            throw new InvalidResponseException('Missing Token in notification');
        }

        $expectedToken = $this->calculateToken();

        if (!hash_equals($expectedToken, $this->data['Token'])) {
            throw new InvalidResponseException('Invalid Token signature');
        }
    }

    public function isSuccessful(): bool
    {
        if (!isset($this->data['Status'])) {
            return false;
        }

        return in_array($this->data['Status'], ['CONFIRMED', 'AUTHORIZED'], true);
    }

    public function getTransactionId(): ?string
    {
        return $this->data['OrderId'] ?? null;
    }

    public function getTransactionReference(): ?string
    {
        return isset($this->data['PaymentId']) ? (string) $this->data['PaymentId'] : null;
    }

    public function getAmount(): ?string
    {
        if (!isset($this->data['Amount'])) {
            return null;
        }

        return number_format($this->data['Amount'] / 100, 2, '.', '');
    }

    public function getStatus(): ?string
    {
        return $this->data['Status'] ?? null;
    }

    public function getMessage(): ?string
    {
        return $this->data['Message'] ?? null;
    }

    public function getCode(): ?string
    {
        return isset($this->data['ErrorCode']) ? (string) $this->data['ErrorCode'] : null;
    }

    /**
     * Calculate expected token from notification data.
     *
     * Same algorithm: collect params (except Token), add Password,
     * sort by key, concatenate values, SHA-256.
     */
    protected function calculateToken(): string
    {
        $data = $this->data;
        $data['Password'] = $this->request->getPassword();

        unset($data['Token'], $data['Receipt'], $data['DATA'], $data['Data']);

        $filtered = array_filter($data, 'is_scalar');

        ksort($filtered);

        $values = implode('', array_map(function ($v) {
            if (is_bool($v)) {
                return $v ? 'true' : 'false';
            }
            return (string) $v;
        }, $filtered));

        return hash('sha256', $values);
    }
}
