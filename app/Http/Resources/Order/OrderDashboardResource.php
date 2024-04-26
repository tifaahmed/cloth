<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDashboardResource extends JsonResource
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
            'order_type'        => $this->type_name,

            'delivery_area'     => $this->delivery_area,
            'delivery_charge'   => $this->delivery_charge,

            'customer_name'     => $this->customer_name,
            'customer_email'    => $this->customer_email,

            'delivery_date'     => $this->delivery_date,
            'delivery_time'     => $this->delivery_time,
            'status'            => $this->status_name,
        ];
    }
}
