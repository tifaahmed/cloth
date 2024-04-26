<?php

namespace App\Http\Resources\Cart;

use App\Helpers\helper;
use App\Http\Resources\Order\ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'vendor_id'     => $this->vendor_id,
            'user_id'       => $this->user_id,

            'item'          => new ItemResource($this->item),
            'item_name'       => $this->item_name,
            'item_image'    => helper::image_path($this->item_image),
            'item_price'       => number_format($this->price,2),

            'tax'           => $this->tax,

            'extras_id'     => new ItemResource($this->extra),

            'qty'           => $this->qty,

        ];
    }
}
