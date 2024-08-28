<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\Contracts;

use GranadaPride\Clickpay\DTO\PaymentDetailsDTO;

interface PaymentGatewayInterface
{
    public function createPaymentPage(PaymentDetailsDTO $details): array;

    public function queryTransaction(string $transactionReference): array;
}
