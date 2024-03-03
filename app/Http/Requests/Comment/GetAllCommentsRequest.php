<?php

namespace App\Http\Requests\Comment;

use App\Models\Comment;
use App\Rules\ValidCommentApprovedValues;
use Illuminate\Foundation\Http\FormRequest;

class GetAllCommentsRequest extends FormRequest
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
        $validValues = implode(',', Comment::approvedValues);
        return [
            'approved' => ['nullable', 'in:' . $validValues]
        ];
    }
}
