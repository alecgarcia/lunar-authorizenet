<?php

namespace alecgarcia\LunarAuthorizeNet\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \alecgarcia\LunarAuthorizeNet\LunarAuthorizeNetPaymentType
 */
class LunarAuthorizeNet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \alecgarcia\LunarAuthorizeNet\LunarAuthorizeNetPaymentType::class;
    }
}
