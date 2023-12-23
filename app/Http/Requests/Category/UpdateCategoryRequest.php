<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
        $category = $this->route('category');

        return [
            'name' => 'required|string',
            'slug' => 'unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'image:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
