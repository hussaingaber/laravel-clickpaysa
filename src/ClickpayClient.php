<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use Exception;
use GranadaPride\Clickpay\Contracts\HttpClientInterface;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;
use GranadaPride\Clickpay\DTO\Payment;
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

    public function createPaymentPage(Payment $payment): array
    {
        try {
            return $this->httpClient->post('payment/request', $this->buildPayload($payment));
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

    private function buildPayload(Payment $payment): array
    {
        return [
            'profile_id' => intval($this->profileId),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'paypage_lang' => $payment->paypageLang,
            'callback' => $payment->callbackUrl,
            'return' => $payment->returnUrl,
            'user_defined' => [
                'udf3' => 'UDF3 Test3',
                'udf9' => 'UDF9 Test9',
            ],
            'customer_details' => $payment->customer->toArray(),
            'shipping_details' => $payment->shipping->toArray(),
            'cart_id' => $payment->cartId,
            'cart_amount' => $payment->cartAmount,
            'cart_description' => $payment->cartDescription,
            'cart_currency' => $this->currency,
            'hide_shipping' => $payment->hideShipping,
        ];
    }
}
