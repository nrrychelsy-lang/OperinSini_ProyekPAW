<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailKampus implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = strtolower(trim((string) $value));

        if (! str_ends_with($email, '.ac.id') && ! str_ends_with($email, '.edu')) {
            $fail('Gunakan email kampus yang berakhiran .ac.id atau .edu.');
        }
    }
}
