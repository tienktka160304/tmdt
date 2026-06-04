<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;


class EmailRule implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public $errorMessage = '';

    public function passes($attribute, $value)
    {

        $value = trim($value);
        if (empty($value)) {
            $this->errorMessage = 'Bạn chưa nhập Email!';
            return false;
        }
        if (!str_contains($value, '@')) {
            $this->errorMessage = 'Email phải chứa ký tự "@". Email.Email@domain.com';
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9]/', $value)) {
            $this->errorMessage = 'Email bắt đầu bằng chữ hoặc số.';
            return false;
        }
        if (!preg_match('/[^a-zA-Z0-9._%+-]/', $value)) {
            $this->errorMessage = 'Email chỉ được chứa các kí tự: chữ, số, . - % + - # $ *';
            return false;
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage = 'Email không hợp lệ, vui lòng nhập đúng định dạng (ví dụ: example@domain.com).';
            return false;
        }
        return true;
    }
    public function message()
    {
        return $this->errorMessage;
    }
}
