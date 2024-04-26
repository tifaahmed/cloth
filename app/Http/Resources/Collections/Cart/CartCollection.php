<?php

namespace App\Http\Resources\Collections\Cart;

use Illuminate\Http\Resources\Json\ResourceCollection;

use App\Http\Resources\Cart\CartResource as ModelResource;

class CartCollection  extends ResourceCollection{

    public function toArray( $request ) {
        return $this -> collection -> map( fn( $model ) => new ModelResource ( $model ) );
    }

    public function with( $request ) {
        return [
            'message' => 'Successful.' ,
            'status'   => true          ,
            'code'   => 200          ,
        ];
    }
}
//