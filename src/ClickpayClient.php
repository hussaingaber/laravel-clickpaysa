<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use Exception;
use GranadaPride\Clickpay\Contracts\HttpClientInterface;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;
use GranadaPride\Clickpay\DTO\PaymentDTO;
use GranadaPride\Clickpay\Exceptions\PaymentException;

class ClickpayClient implements PaymentGatewayInterface
{
    private HttpClientInterface $httpClient;
    private string $profileId;
    private string $currency;

    public function __construct(
        HttpClientInterface $httpClient,
        string              $profileId,
        string              $currency
    ) {
        $this->httpClient = $httpClient;
        $this->profileId = $profileId;
        $this->currency = $currency;
    }

    public function createPaymentPage(PaymentDTO $paymentDTO): array
    {
        try {
            return $this->httpClient->post('payment/request', $this->buildPayload($paymentDTO));
        } catch (Exception $e) {
            throw new PaymentException('Failed to create Clickpay payment page: ' . $e->getMessage());
        }
    }

    public function queryTransaction(string $transactionReference): array
    {
        try {
            return $this->httpClient->post('payment/query', [
                'profile_id' => intval($this->profileId),
                'tran_ref' => $transactionReference,
            ]);
        } catch (Exception $e) {
            throw new PaymentException('Failed to fetch Clickpay query transaction: ' . $e->getMessage());
        }
    }

    public function capturePayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array
    {
        return $this->processTransaction('capture', $transactionReference, $amount, $currency, $cartId, $cartDescription);
    }

    public function refundPayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array
    {
        return $this->processTransaction('refund', $transactionReference, $amount, $currency, $cartId, $cartDescription);
    }

    public function voidPayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array
    {
        return $this->processTransaction('void', $transactionReference, $amount, $currency, $cartId, $cartDescription);
    }

    private function processTransaction(string $transactionType, string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array
    {
        try {
            return $this->httpClient->post('payment/request', [
                'profile_id' => intval($this->profileId),
                'tran_type' => $transactionType,
                'tran_class' => 'ecom',
                'cart_id' => $cartId,
                'cart_currency' => $currency,
                'cart_amount' => $amount,
                'cart_description' => $cartDescription,
                'tran_ref' => $transactionReference,
            ]);

        } catch (Exception $e) {
            throw new PaymentException("Failed to process $transactionType transaction: " . $e->getMessage());
        }
    }


    private function buildPayload(PaymentDTO $paymentDTO): array
    {
        $payload = [
            'profile_id' => intval($this->profileId),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'paypage_lang' => $paymentDTO->paypageLang,
            'callback' => $paymentDTO->callbackUrl,
            'return' => $paymentDTO->returnUrl,
            'user_defined' => [
                'udf3' => 'UDF3 Test3',
                'udf9' => 'UDF9 Test9',
            ],
            'cart_id' => strval($paymentDTO->cartId),
            'cart_amount' => $paymentDTO->cartAmount,
            'cart_description' => $paymentDTO->cartDescription,
            'cart_currency' => $this->currency,
            'hide_shipping' => $paymentDTO->hideShipping,
        ];

        if (isset($paymentDTO->customerDTO)) {
            $payload['customer_details'] = $paymentDTO->customerDTO->toArray();
        }

        if (isset($paymentDTO->shippingDTO)) {
            $payload['shipping_details'] = $paymentDTO->shippingDTO->toArray();
        }

        return $payload;
    }
}
