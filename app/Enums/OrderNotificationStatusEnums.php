<?php

namespace App\Enums;

use Spatie\Enum\Enum;
use ReflectionClass;

final class OrderNotificationStatusEnums extends Enum
{

    public static function mapValueToName(): array
    {
        return [
            1 => 'un-read',
            2 => 'read',
        ];
    }
    public static function mapNameToValue(): array
    {
        return [
            'un-read'   => 1,
            'read'      => 2,

        ];
    }

    public static function getConstantByName($name)
    {
        $array = static::mapValueToName();
        if (isset($array[$name])) {
            return  __($array[$name]);
        }
        return $name;
    }
}