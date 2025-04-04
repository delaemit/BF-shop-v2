<?php

declare(strict_types=1);

namespace Webkul\Core\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class Code implements ValidationRule
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
        if (!preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $value)) {
            $fail('core::validation.code')->translate();
        }
    }
}
