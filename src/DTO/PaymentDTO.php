<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\DTO;

class PaymentDTO
{
    public function __construct(
        public string       $cartId,
        public float        $cartAmount,
        public string       $cartDescription,
        public string       $callbackUrl,
        public string       $returnUrl,
        public string       $paypageLang,
        public bool         $hideShipping = false,
        public ?CustomerDTO $customerDTO = null,
        public ?CustomerDTO $shippingDTO = null,
    ) {
    }
}
