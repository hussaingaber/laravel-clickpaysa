<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\Contracts;

use GranadaPride\Clickpay\DTO\PaymentDTO;

interface PaymentGatewayInterface
{
    public function createPaymentPage(PaymentDTO $details): array;

    public function queryTransaction(string $transactionReference): array;

    public function capturePayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array;

    public function refundPayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array;

    public function voidPayment(string $transactionReference, float $amount, string $currency, string $cartId, string $cartDescription): array;

}
