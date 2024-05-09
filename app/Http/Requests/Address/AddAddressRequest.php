<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddAddressRequest extends FormRequest
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
            'title' => 'required|string',
            'cellphone' => 'required',
            'postalcode' => 'required',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'province' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|min:5|max:400',
            'number' => 'required|numeric',
            'unit' => 'nullable|numeric',
            'name' => 'required',
        ];
    }
}
