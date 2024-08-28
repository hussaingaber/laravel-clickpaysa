<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay;

use GranadaPride\Clickpay\Contracts\HttpClientInterface;
use Illuminate\Support\Facades\Http;

class HttpClient implements HttpClientInterface
{
    private string $serverKey;
    private string $baseUrl;

    public function __construct(string $serverKey, string $baseUrl)
    {
        $this->serverKey = $serverKey;
        $this->baseUrl = $baseUrl;
    }

    public function post(string $url, array $data): array
    {
        $response = Http::withHeaders([
            'authorization' => $this->serverKey,
        ])->baseUrl($this->baseUrl)
            ->post($url, $data);

        return $response->json();
    }
}
