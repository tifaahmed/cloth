<?php

namespace App\Http\Controllers\Traits;

// Models
    use App\Models\Cart;

trait CartTraits {

    public function checkIfItemExistsInCart($item_id) : bool
    {
        return  Cart::where(
            [
                'item_id'=> $item_id,
                'user_id' => auth()->id()
            ])
            ->first() ? 1 : 0 ;
    }
}