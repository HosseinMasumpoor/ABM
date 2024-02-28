<?php

namespace App\Http\Requests\Address;

use App\Models\Address;
use Illuminate\Foundation\Http\FormRequest;

class EditAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $addressId = request()->segment(3);
        $addresses_id = auth()->user()->addresses()->pluck('id')->toArray();
        if (in_array($addressId, $addresses_id)) {
            return true;
        }
        return false;
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
            'name' => 'required',
        ];
    }
}
