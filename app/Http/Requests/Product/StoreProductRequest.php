<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => 'unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array|required',
            'images.*' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048',
            'attributes' => 'array',
            'attributes.*' => 'required',
            'sizes' => 'array|required',
            'sizes.*' => 'required'
        ];
    }
}
