<?php

namespace Omnipay\TBank\Message;

abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $zeroAmountAllowed = false;

    protected string $productionUrl = 'https://securepay.tinkoff.ru/v2/';
    protected string $testUrl = 'https://rest-api-test.tinkoff.ru/v2/';

    protected function getBaseUrl(): string
    {
        return $this->getTestMode() ? $this->testUrl : $this->productionUrl;
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

    /**
     * Generate token for T-Bank API request.
     *
     * Algorithm: collect all params (except Token), add Password,
     * sort by key, concatenate values, SHA-256 hash.
     */
    protected function generateToken(array $data): string
    {
        $data['Password'] = $this->getPassword();

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
