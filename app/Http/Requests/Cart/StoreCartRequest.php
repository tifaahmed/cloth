<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
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
            'extras_id'     => ['required'],
            'extras_name'   => ['required'],
            'extras_price'  => ['required'],

            'variants_id'     => ['required'],
            'variants_name'   => ['required'],
            'variants_price'  => ['required'],
        ];
    }
}
