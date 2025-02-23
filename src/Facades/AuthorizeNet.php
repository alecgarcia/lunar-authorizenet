<?php

namespace alecgarcia\LunarAuthorizeNet\Facades;

use alecgarcia\LunarAuthorizeNet\AuthorizeNet as AuthorizeNetClass;
use Illuminate\Support\Facades\Facade;

/**
 * @see AuthorizeNetClass
 */
class AuthorizeNet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AuthorizeNetClass::class;
    }
}
