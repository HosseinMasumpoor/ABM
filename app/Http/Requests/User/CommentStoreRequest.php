<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'product_id' => 'required|exists:comments,id',
            'text' => 'required|min:2|max:300',
            'rate' => 'required|numeric|min:0|max:5',
            'is_anonymous' => 'required|boolean'
        ];
    }
}
