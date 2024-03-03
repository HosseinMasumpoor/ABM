<?php

namespace App\Rules;

use App\Models\Comment;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCommentApprovedValues implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!in_array($value, Comment::approvedValues)) {
            $fail('مقدار وارد شده معتبر نیست. مقادیر معتبر بین 0،1 و null می باشند');
        }
    }
}
