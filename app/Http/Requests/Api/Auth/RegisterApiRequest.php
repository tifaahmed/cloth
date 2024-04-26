<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "name"                          => ["required", "min:3"],
            "slug"                          => ["nullable", "min:3"],
            "email"                         => ["required", "string", "email", "unique:users,email"],
            "password"                      => ["required", "string", "min:3", "confirmed"],
            "mobile"                        => ["nullable", "min:10"],
            "image"                         => ["required", "image", "mimes:jpeg,png,jpg,gif", "max:2048"],
            "google_id"                     => ["nullable"],
            "facebook_id"                   => ["nullable"],
            "login_type"                    => ["required", "in:normal,google,facebook"],
            "type"                          => ["required", "in:1,2,3,4"],
            "description"                   => ["nullable", "string"],
            "city_id"                       => ["nullable", "integer"],
            "area_id"                       => ["nullable", "integer"],
            "plan_id"                       => ["nullable", "integer"],
            "purchase_amount"               => ["nullable", "numeric"],
            "purchase_date"                 => ["nullable", "datetime"],
            "available_on_landing"          => ["required", "in:1,2"],
            "payment_id"                    => ["nullable", "integer"],
            "payment_type"                  => ["nullable"],
            "free_plan"                     => ["required", "numeric"],
            "is_delivery"                   => ["nullable", "in:1,2"],
            "allow_without_subscription"    => ["nullable", "in:1,2"],
            "is_verified"                   => ["nullable", "in:1,2"],
            "is_available"                  => ["nullable", "in:1,2"],
            "remember_token"                => ["nullable"],
            "license_type"                  => ["nullable"],
        ];
    }
}