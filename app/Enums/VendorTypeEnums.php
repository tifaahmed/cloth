<?php

namespace App\Enums;

use Spatie\Enum\Enum;
use ReflectionClass;

final class VendorTypeEnums extends Enum
{

    public static function mapValueToName(): array
    {
        return [
            1   => 'admin',
            2   => 'vendor',
            3   => 'driver',
            4   => 'customer',
        ];
    }
    public static function mapNameToValue(): array
    {
        return [
            'admin'         => 1,
            'vendor'        => 2,
            'driver'        => 3,
            'customer'      => 4,
        ];
    }
    public static function getConstantByName($name)
    {
        $array = static::mapValueToName() ;
        if (  isset($array[$name]) ) {
            return  __($array[$name]) ;
        }
        return $name;
    }
    public static function getDefault(): string
    {
        $array =self::mapNameToValue();
        return $array['online'];
    }
}
