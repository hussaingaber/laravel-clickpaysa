<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\DTO;

class Payment
{
    public function __construct(
        public string   $cartId,
        public float    $cartAmount,
        public string   $cartDescription,
        public Customer $customer,
        public Customer $shipping,
        public string   $callbackUrl,
        public string   $returnUrl,
        public string   $paypageLang,
        public bool     $hideShipping = false
    ) {
    }
}
