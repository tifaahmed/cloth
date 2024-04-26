<?php

namespace App\Enums;

use Spatie\Enum\Enum;
use ReflectionClass;

final class OrderTypeEnums extends Enum
{

    public static function mapValueToName(): array
    {
        return [
            1 => 'Delivery (Dine-in - POS)',
            2 => 'Pickup (TakeAway - POS)',
            3 => 'Dine In (Front)',
        ];
    }
    public static function mapNameToValue(): array
    {
        return [
            'Delivery (Dine-in - POS)'  => 1,
            'Pickup (TakeAway - POS)'   => 2,
            'Dine In (Front)'           => 3,

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