<?php

namespace App\Http\Requests;

use App\Rules\PassRule;
use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $idus = $this->route('id') ?? Auth::id();
        $rules = [
            'last_name' => 'required|string|min:2|max:50|regex:/^[\p{L} ]+$/u',
            'first_name' => 'required|string|min:2|max:50|regex:/^[\p{L} ]+$/u',
            'roles' => 'required',
            'account_lock' => 'required|boolean',
            'phone' => [
                'required',
                'regex:/^(0[1-9][0-9]{8,9})$/',
                Rule::unique('users', 'phone')->ignore($idus)
            ],
            'gender' => 'required|integer|in:1,2',
            'address' => 'required|string|min:5|max:255',
            'email' => [
                new EmailRule,
                Rule::unique('users', 'email')->ignore($idus)
            ],
            'password' => [
                'required',
                new PassRule,
            ]

        ];
        if ($this->hasFile('avatar')) {
            $rules['avatar'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        return $rules;
    }
    public function messages(): array
    {
        return [
            'last_name.required' => 'Họ không được để trống.',
            'last_name.string' => 'Họ phải là chuỗi ký tự.',
            'last_name.min' => 'Họ phải có ít nhất 2 ký tự.',
            'last_name.max' => 'Họ không được vượt quá 50 ký tự.',
            'last_name.regex' => 'Họ chỉ được chứa chữ cái và khoảng trắng.',

            'first_name.required' => 'Tên không được để trống.',
            'first_name.string' => 'Tên phải là chuỗi ký tự.',
            'first_name.min' => 'Tên phải có ít nhất 2 ký tự.',
            'first_name.max' => 'Tên không được vượt quá 50 ký tự.',
            'first_name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng.',

            'roles.required' => 'Cấp bậc không được để trống.',

            'account_lock.required' => 'Trạng thái tài khoản không được để trống.',
            'account_lock.boolean' => 'Trạng thái tài khoản phải là 1 (Hoạt động) hoặc 0 (Đang khóa).',

            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',
            'phone.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',

            'genders.required' => 'Giới tính không được để trống.',
            'genders.integer' => 'Giới tính phải là số nguyên.',
            'genders.in' => 'Giới tính không hợp lệ.',

            'address.required' => 'Địa chỉ không được để trống.',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.min' => 'Địa chỉ phải có ít nhất 5 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            'avatar.required' => 'Ảnh không được để trống.',
            'avatar.image' => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg, gif.',
            'avatar.max' => 'Ảnh không được lớn hơn 2MB.',

            'password.required' => 'Mật khẩu không được để trống!',
        ];
    }
}
