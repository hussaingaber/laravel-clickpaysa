<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use Exception;
use GranadaPride\Clickpay\DTO\CustomerDetails;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
use RuntimeException;

class Clickpay
{
    private string $currency;

    private string $serverKey;

    private string $profileId;

    protected string $cartId;

    protected float $cartAmount;

    protected string $cartDescription;

    protected CustomerDetails $customerDetails;

    protected CustomerDetails $shippingDetails;

    protected string $callbackUrl;

    protected string $returnUrl;

    protected string $paypageLang;

    protected bool $hideShipping = false;

    public function __construct()
    {
        $this->currency = config('clickpay.currency');
        $this->serverKey = config('clickpay.server_key');
        $this->profileId = config('clickpay.profile_id');
    }

    public static function make(): static
    {
        return new static();
    }

    public function setCart(string $cartId, float $cartAmount, string $cartDescription): static
    {
        if ($cartAmount <= 0) {
            throw new InvalidArgumentException('Cart amount must be greater than zero.');
        }

        $this->cartId = $cartId;
        $this->cartAmount = $cartAmount;
        $this->cartDescription = $cartDescription;

        return $this;
    }

    public function getCart(): array
    {
        return [
            'cart_id' => $this->cartId,
            'cart_amount' => $this->cartAmount,
            'cart_description' => $this->cartDescription,
            'cart_currency' => $this->currency,
        ];
    }

    public function setCustomer(CustomerDetails $details): static
    {
        $this->customerDetails = $details;

        return $this;
    }

    public function getCustomer(): array
    {
        return $this->customerDetails->toArray();
    }

    public function setShipping(CustomerDetails $details): static
    {
        $this->shippingDetails = $details;

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shippingDetails->toArray();
    }

    public function useCustomerForShipping(): static
    {
        $this->shippingDetails = $this->customerDetails;

        return $this;
    }

    public function setCallbackUrl(string $callbackUrl): static
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setReturnUrl(string $returnUrl): static
    {
        $this->returnUrl = $returnUrl;

        return $this;
    }

    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    public function setPaypageLang(string $paypageLang): static
    {
        $this->paypageLang = $paypageLang;

        return $this;
    }

    public function getPaypageLang(): string
    {
        return $this->paypageLang;
    }

    public function setHideShipping(bool $hide): static
    {
        $this->hideShipping = $hide;

        return $this;
    }

    public function getHideShipping(): bool
    {
        return $this->hideShipping;
    }

    protected function initialize(): PendingRequest
    {
        return Http::withHeaders([
            'authorization' => $this->serverKey,
        ])->baseUrl($this->getBaseUrl());
    }

    public function paypage()
    {
        try {
            return $this->initialize()
                ->post('payment/request', $this->paypagePayload())
                ->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to create Clickpay payment page: ' . $e->getMessage());
        }
    }

    public function queryTransaction(string $transactionReference)
    {
        try {
            $this->initialize()
                ->post('payment/query', [
                    'profile_id' => intval($this->profileId),
                    'tran_ref' => $transactionReference,
                ])->json();
        } catch (Exception $e) {
            throw new RuntimeException('Failed to fetch Clickpay query transaction: ' . $e->getMessage());
        }
    }

    protected function paypagePayload(): array
    {
        return [
            'profile_id' => intval($this->profileId),
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'paypage_lang' => $this->paypageLang,
            'callback' => $this->callbackUrl,
            'return' => $this->returnUrl,
            'user_defined' => [
                'udf3' => 'UDF3 Test3',
                'udf9' => 'UDF9 Test9',
            ],
            'customer_details' => $this->getCustomer(),
            'shipping_details' => $this->getShipping(),
            'cart_id' => $this->cartId,
            'cart_amount' => $this->cartAmount,
            'cart_description' => $this->cartDescription,
            'cart_currency' => $this->currency,
        ];
    }

    protected function getBaseUrl(): string
    {
        return 'https://secure.clickpay.com.sa';
    }
}