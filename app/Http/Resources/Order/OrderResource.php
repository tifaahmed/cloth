<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'vendor'            => $this->vendorInfo->name,
            'order_number'      => $this->order_number,
            'payment_type'      => $this->paymentTypeName,
            'payment'           => $this->getCustomPaymentId(),
            'sub_total'         => $this->sub_total,
            'tax'               => $this->tax,
            'grand_total'       => $this->grand_total,
            'order_type'        => $this->type_name,
            'table'             => $this->getTableQr(),

            'address'           => $this->address,
            'pincode'           => $this->pincode,
            'building'          => $this->building,
            'landmark'          => $this->landmark,

            'block'             => $this->block,
            'street'            => $this->street,
            'house_num'         => $this->house_num,

            'delivery_area'     => $this->delivery_area,
            'delivery_charge'   => $this->delivery_charge,

            'discount_amount'   => $this->discount_amount,
            'couponcode'        => $this->couponcode,

            'order_notes'       => $this->order_notes,
            'customer_name'     => $this->customer_name,
            'customer_email'    => $this->customer_email,
            'mobile'            => $this->mobile,

            'delivery_date'     => $this->delivery_date,
            'delivery_time'     => $this->delivery_time,
            'order_from'        => $this->order_from,

            'status'            => $this->status_name,
            'is_notification'   => $this->notification_status,

            'latitude'          => $this->latitude,
            'longitude'         => $this->longitude,
            'branch_name'       => $this->branch ? $this->branch->name : null,

            'orderDetails'      => OrderDetailResource::collection($this->orderDetails),
            'created_at'        => Carbon::parse($this->created_at)->format('Y-m-d'),
        ];
    }

    private function getCustomPaymentId()
    {
        return $this->payment_id != null ? $this->paymentInfo->PaymentNameModified : null;
    }

    private function getTableQr()
    {
        return $this->tableqr ? $this->tableqr->name : null;
    }
}
