<?php

namespace App\Scopes;


trait ScopePriceFilter
{
    // price_range
    public function scopePriceRange($query, $price_from, $price_to){
        return $query->whereBetween('item_price', [$price_from, $price_to]);
    }
}
// used in Item
