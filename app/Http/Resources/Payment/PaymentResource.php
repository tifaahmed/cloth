<?php

namespace App\Http\Resources\Payment;

use App\Helpers\helper;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_name'          => $this->payment_name_modified, // Use modified attribute
            'currency'              => $this->currency,
            'image'                 => helper::image_path($this->image),
            'public_key'            => $this->public_key,
            'secret_key'            => $this->secret_key,
            'environment'           => $this->environment,
            'bank_name'             => $this->bank_name,
            'account_number'        => $this->account_number,
            'account_holder_name'   => $this->account_holder_name,
            'bank_ifsc_code'        => $this->bank_ifsc_code,
            'is_available'          => $this->is_available ? 'Yes' : 'No',
            'is_active'             => $this->is_activate ? 'Yes' : 'No', // Fix typo from is_activate to is_active
        ];
    }
}
