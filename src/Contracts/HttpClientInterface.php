<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\Contracts;

interface HttpClientInterface
{
    public function post(string $url, array $data): array;
}
