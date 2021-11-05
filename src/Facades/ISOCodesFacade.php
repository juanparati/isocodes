<?php

namespace Juanparati\ISOCodes\Facades;

use Illuminate\Support\Facades\Facade;
use Juanparati\ISOCodes\ISOCodes;

class ISOCodesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ISOCodes::class;
    }
}
