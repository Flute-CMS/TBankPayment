<?php

namespace Omnipay\TBank\Message;

class CompletePurchaseRequest extends AbstractRequest
{
    public function getData(): array
    {
        $data = $this->httpRequest->request->all();

        if (empty($data)) {
            $content = $this->httpRequest->getContent();
            if (!empty($content)) {
                $json = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                    $data = $json;
                }
            }
        }

        if (empty($data['Token'])) {
            logs()->warning('tbank.webhook.raw_debug', [
                'post_keys' => array_keys($this->httpRequest->request->all()),
                'content_length' => strlen($this->httpRequest->getContent()),
                'content_type' => $this->httpRequest->headers->get('Content-Type'),
                'content_preview' => mb_substr($this->httpRequest->getContent(), 0, 300),
                'data_keys' => array_keys($data),
            ]);
        }

        return $data;
    }

    public function sendData($data): CompletePurchaseResponse
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}
