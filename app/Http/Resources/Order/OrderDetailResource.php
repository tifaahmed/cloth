<?php

namespace App\Http\Resources\Order;

use App\Helpers\helper;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'id'                => $this->id,
            'item'              => new ItemResource($this->item),
            'item_name'         => $this->item_name,
            'item_image'        => helper::image_path($this->item_image),
            'extra'             => new ExtraResource($this->extra),
            'extra_name'        => $this->extra_name,
            'extras_price'      => $this->extras_price,
            'price'             => number_format($this->price,2),
            'variant'           => new VariantResource($this->variant),
            'variants_name'     => $this->variants_name,
            'variants_price'    => $this->variants_price,
            'qty'               => $this->qty,
        ];
    }
}
