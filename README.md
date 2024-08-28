# Clickpaysa Payment Gateway Integration for Laravel

## Table of Contents

1. [Description](#description)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Usage](#usage)
    - [Creating a PayPage](#create-paypage)
    - [Querying Transactions](#query-transaction)
    - [Capturing a Payment](#capturing-a-payment)
    - [Refunding a Payment](#refunding-a-payment)
    - [Voiding a Payment](#voiding-a-payment)
5. [Troubleshooting](#troubleshooting)
6. [Testing](#testing)
7. [Contribution](#contribution)
8. [License](#license)

## Description

The `laravel-clickpaysa` package provides an easy way to integrate the Clickpaysa payment gateway into your Laravel
applications. This package adheres to SOLID principles and uses modern PHP practices, ensuring your code is
maintainable, testable, and scalable.

## Installation

You can install the package via composer:

```bash
composer require granada-pride/clickpaysa
```

## Configuration

After installation, publish the configuration file using the following command:

```bash
php artisan vendor:publish --provider="GranadaPride\Clickpay\ClickpayServiceProvider"
```

This will create a `config/clickpay.php` file. Here is an example of what the configuration file might look like:

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Clickpay Profile ID
    |--------------------------------------------------------------------------
    |
    | This is your Clickpay profile ID, which is required for making API 
    | requests. Ensure that this value is provided in your environment 
    | configuration file (.env).
    |
    */

    'profile_id' => env('CLICKPAY_PROFILE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Clickpay Server Key
    |--------------------------------------------------------------------------
    |
    | Your Clickpay server key is used to authenticate API requests. Make 
    | sure to keep this value secure and do not expose it in your version 
    | control system. It should be stored in the .env file.
    |
    */

    'server_key' => env('CLICKPAY_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    |
    | The currency in which payments will be processed by default. You can 
    | change this to any currency supported by Clickpay (e.g., USD, EUR).
    | This value can also be configured in your .env file.
    |
    */

    'currency' => env('CLICKPAY_CURRENCY', 'SAR'),

    /*
    |--------------------------------------------------------------------------
    | Clickpay API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Clickpay API. This is typically the endpoint where 
    | API requests are sent. You can change this value if Clickpay provides 
    | a different URL for specific regions or environments (e.g., testing).
    |
    */

    'base_url' => env('CLICKPAY_BASE_URL', 'https://secure.clickpay.com.sa'),

];
```

## Environment Variables

Make sure to set the required environment variables in your `.env` file:

```dotenv
CLICKPAY_PROFILE_ID=your_profile_id
CLICKPAY_SERVER_KEY=your_server_key
CLICKPAY_CURRENCY=SAR
CLICKPAY_BASE_URL=https://secure.clickpay.com.sa
```

## Usage

### Creating a PayPage

Here's how to create a payment page using this package:

```php
use GranadaPride\Clickpay\ClickpayClient;
use GranadaPride\Clickpay\DTO\Customer;
use GranadaPride\Clickpay\DTO\Payment;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;

$clickpay = app(PaymentGatewayInterface::class);

// Set Customer Information using the Customer DTO
$customer = new Customer(
    name: 'Ahmad Mohamed',
    phone: '+123456789',
    email: 'ahmad_mohamed@example.com',
    street: '123 Main St',
    city: 'Cityville',
    state: 'Stateland',
    country: 'KSA',
    zipCode: '12345'
);

// Use Customer Information for Shipping if it's the same
$shipping = $customer;

// Set Payment Details
$payment = new Payment(
    cartId: 'CART123',
    cartAmount: 150.00,
    cartDescription: 'Sample Cart Description',
    customer: $customer,
    shipping: $shipping,
    callbackUrl: 'https://yourdomain.com/callback',
    returnUrl: 'https://yourdomain.com/return',
    paypageLang: 'ar',
    hideShipping: false  // Set to true if you want to hide shipping details on the payment page
);

// Generate Payment Page
$response = $clickpay->createPaymentPage($payment);

dd($response);
```

### Querying Transactions

You can also query a transaction using its reference:

```php
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;

$clickpay = app(PaymentGatewayInterface::class);

$response = $clickpay->queryTransaction('TST2422201903602');

dd($response);
```

### Capturing a Payment

The `capturePayment` method allows you to capture a previously authorized transaction. This is typically used when a
payment is authorized at the time of order placement, but you only want to capture the funds when the goods are shipped.

**Example:**

```php
use GranadaPride\Clickpay\ClickpayClient;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;

$clickpay = app(PaymentGatewayInterface::class);

$response = $clickpay->capturePayment(
    transactionReference: 'TST2112600164150',
    amount: 1.3,
    currency: 'SAR',
    cartId: 'cart_66666',
    cartDescription: 'Capture reason'
);

dd($response);
```

### Refunding a Payment

The `refundPayment` method is used to refund a previously completed transaction. This can be useful when a customer
requests a refund for their order.

**Example:**

```php
use GranadaPride\Clickpay\ClickpayClient;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;

$clickpay = app(PaymentGatewayInterface::class);

$response = $clickpay->refundPayment(
    transactionReference: 'TST2016700000692',
    amount: 1.3,
    currency: 'SAR',
    cartId: 'cart_66666',
    cartDescription: 'Refund reason'
);

dd($response);
```

### Voiding a Payment

The `voidPayment` method allows you to void a previously authorized or captured transaction. This is typically used when
an order is canceled before it is fulfilled.

**Example:**

```php
use GranadaPride\Clickpay\ClickpayClient;
use GranadaPride\Clickpay\Contracts\PaymentGatewayInterface;

$clickpay = app(PaymentGatewayInterface::class);

$response = $clickpay->voidPayment(
    transactionReference: 'TST2016700000692',
    amount: 1.3,
    currency: 'SAR',
    cartId: 'cart_66666',
    cartDescription: 'Void reason'
);

dd($response);
```

## Troubleshooting

### Common Issues

- **Invalid Credentials:** Ensure that your `profile_id` and `server_key` in the configuration file are correct.
- **Unsupported Region:** Double-check that the `base_url` in your configuration file is valid and supported by
  Clickpay.
- **Transaction Failure:** Verify the transaction data (e.g., cart amount, customer details) to ensure it meets
  Clickpay's requirements.

If you encounter other issues, refer to the [Clickpay API Documentation](https://secure.clickpay.com.sa) for more
details.

## Testing

Soon...

## Contribution

Contributions are welcome! If you’d like to contribute to this package, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Write your code and ensure it is well-documented.
4. Submit a pull request with a clear description of your changes.

## License

This package is open-source software licensed under the MIT License. Please see the `License` File for more information.


