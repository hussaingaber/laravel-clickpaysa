<?php

declare(strict_types=1);

namespace GranadaPride\Clickpay\Facades;

use Illuminate\Support\Facades\Facade;

class Clickpay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'clickpay';
    }
}
