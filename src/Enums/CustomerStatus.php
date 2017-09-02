<?php

namespace Viviniko\Customer\Enums;

class CustomerStatus
{
    const ACTIVE = 1;
    const BANNED = 0;

    public static function values()
    {
        return [
            static::ACTIVE => 'Active',
            static::BANNED => 'Banned',
        ];
    }
}