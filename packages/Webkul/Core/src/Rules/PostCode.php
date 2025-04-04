<?php

declare(strict_types=1);

namespace Webkul\Core\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class PostCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\s-]*[a-zA-Z0-9]$/', $value)) {
            $fail('core::validation.postcode')->translate();
        }
    }
}
