<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\Contracts;

use GranadaPride\Clickpay\DTO\Payment;

interface PaymentGatewayInterface
{
    public function createPaymentPage(Payment $details): array;

    public function queryTransaction(string $transactionReference): array;
}
