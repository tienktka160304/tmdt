<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDateRange implements ValidationRule
{
    protected $startDate;

    public function __construct($startDate)
    {
        $this->startDate = $startDate;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value || !$this->startDate) {
            return;
        }

        if (strtotime($value) <= strtotime($this->startDate)) {

            $fail('Thời gian kết thúc phải sau thời gian bắt đầu.');
        }
    }
}