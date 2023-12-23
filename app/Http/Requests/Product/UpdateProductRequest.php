<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use App\Rules\SizeUniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $product = $this->route('product');

        return [
            'name' => 'required',
            'slug' => 'unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric',
            'offPrice' => 'nullable|numeric',
            'off_date_from' => 'required_with:offPrice|date',
            'off_date_to' => 'required_with:offPrice|date',
            'color' => 'required',
            'colorCode' => 'required',
            'images' => 'array',
            'images.*' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'image' => 'image:jpeg,png,jpg,gif,svg|max:2048',
            'attributes' => 'array',
            'attributes.*' => 'required',
            'sizes' => ['array', new SizeUniqueRule($product)],
            // 'sizes.*' => 'required'
        ];
    }
}
