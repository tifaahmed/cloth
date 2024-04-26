<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'name.*'    => ['required', 'array'],
            'image'     => ['nullable', 'image', 'max:2048'],
            'is_available'  => ['boolean'],
        ];
    }

    public function messages()
    {
        return [
            // 'name_ar.required' => 'The Arabic name field is required.',
            // 'name_ar.min' => 'The Arabic name must be at least :min characters.',
            // 'name_ar.string' => 'The Arabic name must be a string.',
            // 'name_en.required' => 'The English name field is required.',
            // 'name_en.min' => 'The English name must be at least :min characters.',
            // 'name_en.string' => 'The English name must be a string.',
            'image.image' => 'The file must be an image.',
            'image.max' => 'The image size must not exceed 2MB.'
        ];
    }
}
