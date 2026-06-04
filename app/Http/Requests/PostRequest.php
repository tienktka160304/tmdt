<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'title' => 'required|max:255',
            'id_category' => 'required|integer',
            'short_description' => 'required|max:500',
            'content' => 'required',
            'hot' => 'nullable|in:0,1',
        ];
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpg,png,jpeg,gif|max:4048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:4048';
        }

        return $rules;
    }


    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề bài viết không được để trống.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'id_category.required' => 'Vui lòng chọn danh mục bài viết.',
            'id_category.integer' => 'Danh mục không hợp lệ.',

            'short_description.required' => 'Mô tả ngắn không được để trống.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 150 ký tự.',

            'content.required' => 'Nội dung bài viết không được để trống.',

            'published_date.date' => 'Ngày xuất bản không hợp lệ.',

            'image.required' => 'Vui lòng chọn hình ảnh.',
            'image.image' => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng jpg, jpeg hoặc png.',
            'image.max' => 'Dung lượng hình ảnh không được vượt quá 4MB.',
        ];
    }
}
