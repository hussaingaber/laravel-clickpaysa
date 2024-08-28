<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\DTO;

class PaymentDetailsDTO
{
    public function __construct(
        public string          $cartId,
        public float           $cartAmount,
        public string          $cartDescription,
        public CustomerDetails $customer,
        public CustomerDetails $shipping,
        public string          $callbackUrl,
        public string          $returnUrl,
        public string          $paypageLang,
        public bool            $hideShipping = false
    ) {
    }
}
