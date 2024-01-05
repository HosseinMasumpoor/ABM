<?php

namespace App\Http\Requests\Order;

use App\Rules\ValidAddressIdRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*' => 'required',
            'address_id' => ['required', 'exists:addresses,id', new ValidAddressIdRule],
            'description' => 'nullable|string',
            'items.*.id',
            'items.*.sizes.id',
            'items.*.sizes.size',
            'items.*.price',
            'items.*.quantity'
        ];
    }
}
