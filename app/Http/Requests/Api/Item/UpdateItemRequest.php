<?php

namespace App\Http\Requests\Api\Item;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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

    public function rules()
    {
        dd($this->all());
        return [
            'cat_id'                => ['required'],

            'item_name.*'           => ['required', 'string', 'min:3', 'max:255'],
            'description.*'         => ['required', 'string', 'min:3'],
            'start_time'            => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'end_time'              => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
            'image'                 => ['nullable', 'image', 'max:1024'],

            'item_price'            => ['required', 'numeric'],
            'item_original_price'   => ['required', 'numeric'],
            'tax'                   => ['nullable', 'numeric'],


            'has_variants'                               => ['required', 'in:1,2'],
            'variations.*.id'                            => ['required_if:has_variants,1'],
            'variations.*.name.*'                        => ['required_if:has_variants,1'],
            'variations.*.price'                         => ['required_if:has_variants,1'],
            'variations.*.variation_original_price'      => ['required_if:has_variants,1'],

            'has_extras'            => ['required', 'in:1,2'],
            'extras.*'              => ['required_if:has_extras,1'],
            'extras.*.name.*'       => ['required_if:has_variants,1'],
            'extras.*.price'        => ['required_if:has_variants,1'],
        ];
    }
}
