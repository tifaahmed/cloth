<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                            => $this->id,
            'name'                          => $this->name,
            'slug'                          => $this->slug,
            'email'                         => $this->email,
            'mobile'                        => $this->mobile,
            'image'                         => $this->image,
            'google_id'                     => $this->google_id ?? '',
            'facebook_id'                   => $this->facebook_id ?? '',
            'login_type'                    => $this->login_type,
            'type'                          => $this->type_name,
            'description'                   => $this->description ?? '',
            'city_id'                       => $this->city?->name ?? '',
            'area_id'                       => $this->area?->name ?? '',
            'plan_id'                       => $this->plan?->name ?? '',
            'purchase_amount'               => $this->purchase_amount ?? '',
            'purchase_date'                 => Carbon::parse($this->purchase_date)->format('Y-m-d') ?? '',
            'available_on_landing'          => $this->available_on_landing ? 'Yes' : 'No',
            'payment_type'                  => $this->payment_type_name,
            'free_plan'                     => $this->free_plan ? 'Yes' : 'No',
            'is_delivery'                   => $this->is_delivery ? 'Yes' : 'No',
            'allow_without_subscription'    => $this->allow_without_subscription ? 'Yes' : "No",
            'is_verified'                   => $this->is_verified  ? 'Yes' : "No",
            'is_available'                  => $this->is_available  ? 'Yes' : "No",
            'license_type'                  => $this->license_type,
        ];
    }
}
