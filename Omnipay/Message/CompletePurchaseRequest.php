<?php

namespace Omnipay\TBank\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        return $this->httpRequest->request->all();
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
