<?php

namespace App\Http\Resources\Category;

use App\Helpers\helper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'vendor'        => $this->vendor->name,
            'name'          => $this->name_translated,
            'slug'          => $this->slug,
            'image'         => helper::image_path($this->image),
            'is_available'  => $this->is_available ? 'Yes' : 'No',
            'is_deleted'    => $this->is_deleted ? 'Yes' : 'No',
            'created_at'    => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }
}
