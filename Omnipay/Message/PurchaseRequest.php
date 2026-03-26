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
        $url = $this->getBaseUrl() . 'Init';

        $httpResponse = $this->httpClient->request(
            'POST',
            $url,
            ['Content-Type' => 'application/json'],
            json_encode($data),
        );

        $body = $httpResponse->getBody()->getContents();
        $responseData = json_decode($body, true);

        if ($responseData === null && $body !== '') {
            logs()->error('tbank.purchase.invalid_response', [
                'url' => $url,
                'http_status' => $httpResponse->getStatusCode(),
                'body_preview' => mb_substr($body, 0, 500),
            ]);
        }

        if (is_array($responseData) && ($responseData['Success'] ?? true) === false) {
            logs()->warning('tbank.purchase.api_error', [
                'error_code' => $responseData['ErrorCode'] ?? null,
                'message' => $responseData['Message'] ?? null,
                'details' => $responseData['Details'] ?? null,
                'order_id' => $data['OrderId'] ?? null,
            ]);
        }

        return $this->response = new PurchaseResponse($this, $responseData ?? []);
    }
}
