<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PassRule implements Rule
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
            $this->errorMessage = 'Bạn chưa nhập mật khẩu!';
            return false;
        }
        if (strlen($value) < 6 || strlen($value) > 20) {
            $this->errorMessage = 'Mật khẩu phải có từ 6 đến 20 ký tự.';
            return false;
        }
        if (!preg_match('/^[a-zA-Z0-9]/', $value)) {
            $this->errorMessage = 'Password bắt đầu bằng chữ hoặc số.';
            return false;
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errorMessage = 'Password có ít nhất một chữ hoa.';
            return false;
        }
        if (!preg_match('/[a-z]/', $value)) {
            $this->errorMessage = 'Password có ít nhất một chữ thường.';
            return false;
        }
        if (!preg_match('/\d/', $value)) {
            $this->errorMessage = 'Password có ít nhất một số.';
            return false;
        }
        if (!preg_match('/[\W_]/', $value)) {
            $this->errorMessage = 'Password có ít nhất một kí tự đặc biệt.';
            return false;
        }

        return true;
    }
    public function message()
    {
        return $this->errorMessage;
    }
}
