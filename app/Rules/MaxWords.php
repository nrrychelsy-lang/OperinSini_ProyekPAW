<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWords implements ValidationRule
{
    public function __construct(private int $max = 500) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $words = preg_split('/\s+/u', trim((string) $value), -1, PREG_SPLIT_NO_EMPTY);

        if (count($words) > $this->max) {
            $fail("Maksimal {$this->max} kata (saat ini " . count($words) . ' kata).');
        }
    }
}
