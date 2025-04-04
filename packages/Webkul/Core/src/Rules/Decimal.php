<?php

declare(strict_types=1);

namespace Webkul\Core\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class Decimal implements ValidationRule
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
        if (!preg_match('/^\d*(\.\d{1,4})?$/', $value)) {
            $fail('core::validation.decimal')->translate();
        }
    }
}
