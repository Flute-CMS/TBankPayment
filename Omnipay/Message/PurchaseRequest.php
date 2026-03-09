<?php

namespace Omnipay\TBank\Message;

class PurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $this->validate('terminalKey', 'amount', 'transactionId');

        $data = [
            'TerminalKey' => $this->getTerminalKey(),
            'Amount' => (int) round((float) $this->getAmount() * 100),
            'OrderId' => $this->getTransactionId(),
            'Description' => $this->getDescription() ?: 'Payment #' . $this->getTransactionId(),
        ];

        if ($this->getReturnUrl()) {
            $data['SuccessURL'] = $this->getReturnUrl();
        }

        if ($this->getCancelUrl()) {
            $data['FailURL'] = $this->getCancelUrl();
        }

        if ($this->getNotifyUrl()) {
            $data['NotificationURL'] = $this->getNotifyUrl();
        }

        $data['Token'] = $this->generateToken($data);

        return $data;
    }

    public function getNotifyUrl(): ?string
    {
        return $this->getParameter('notifyUrl');
    }

    public function setNotifyUrl(mixed $value): self
    {
        return $this->setParameter('notifyUrl', $value);
    }

    public function sendData($data): PurchaseResponse
    {
        $httpResponse = $this->httpClient->request(
            'POST',
            $this->baseUrl . 'Init',
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );

        $responseData = json_decode($httpResponse->getBody()->getContents(), true);

        return $this->response = new PurchaseResponse($this, $responseData);
    }
}
