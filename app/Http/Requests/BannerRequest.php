<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
        $rules = [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'title1' => 'required|string|max:255',
            'title2' => 'string|max:255',
            'sort' => 'integer',
            'position' => 'integer',
            'link' => 'required|string|max:255',
        ];
        $rules['image'] = $this->isMethod('post')
            ? 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
            : 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048';
        return $rules;
    }
    public function messages(): array
    {
        return [
            'image.required' => 'Không được để trống!',
            'image.image' => 'Không phải file hình ảnh!',
            'image.mimes' => 'File hình ảnh phải có đuôi(jpg,png,jpeg,gif,svg)',
            'image.max' => 'Không được quá 2MB!',
            'title1.required' => 'Không được để trống!',
            'title1.string' => 'Phải là chuỗi ký tự!',
            'link.required' => 'Không được để trống!',
            'link.string' => 'Phải là chuỗi ký tự!',
            'link.max' => 'Không được quá 255 ký tự!',
        ];
    }
}
