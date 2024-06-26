<?php

namespace App\Http\Resources\Order;

use App\Helpers\helper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'id'                    => $this->id,
            'name'                  => $this->item_name,
            'vendor'                => $this->vendor->name,
            'slug'                  => $this->slug,
            'category'              => $this->category_info->name,
            'description'           => $this->description,
            'price'                 => number_format($this->item_price,2),
            'item_original_price'   => $this->item_original_price,
            'image'                 => helper::image_path($this->item_image),
            'tax'                   => $this->tax,
            'is_available'          => $this->is_available ? 'Yes' : "No",
            'has_variants'          => $this->has_variants ? "Yes" : "No",
            'has_variants'          => $this->has_variants ? "Yes" : "No",
            'start_time'            => $this->start_time_format,
            'end_time'              => $this->end_time_format,
            'created_at'            => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
