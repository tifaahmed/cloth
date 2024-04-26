<?php

namespace App\Enums;

use Spatie\Enum\Enum;
use ReflectionClass;

final class OrderStatusEnums extends Enum
{

    public static function mapValueToName(): array
    {
        // 3 => 'Dine In (Front)',
        // pending => (confirmed || cancelled )=> (processing = cooking) => done => delivered
        // 2 => 'Pickup (TakeAway - POS)'
        // pending => (confirmed || cancelled )=> (processing = cooking) => done => delivered
        // 1 => 'Delivery (Dine-in - POS)',
        // pending => (confirmed || cancelled )=> (processing = cooking) => done => in delivery => delivered

        return [
            1 => 'pending',
            2 => 'processing', // cooking
            3 => 'delivered',
            4 => 'cancelled',
            5 => 'confirmed',
            6 => 'done',
            7 => 'in_delivery',
            8 => 'rejected',
        ];
    }
    public static function mapNameToValue(): array
    {
        return [
            'pending'       => 1,
            'processing'    => 2,
            'delivered'     => 3,
            'cancelled'     => 4,
            'confirmed'     => 5,
            'done'          => 6,
            'in_delivery'   => 7,
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
