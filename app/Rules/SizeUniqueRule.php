<?php

namespace App\Rules;

use App\Models\Product;
use App\Models\Size;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SizeUniqueRule implements ValidationRule
{

    public function __construct(private Product $product)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentSizes = $this->product->sizes()->whereNotIn('id', request()->deletingSizes ?? [])->pluck('size')->toArray();
        foreach ($value as $size) {
            if (in_array($size["size"], $currentSizes))
                $fail("اندازه وارد شده برای این محصول وجود دارد");
        }
    }
}
