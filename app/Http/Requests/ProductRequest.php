<?php

namespace App\Http\Requests;

use App\Rules\ValidProduct;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả user, bạn có thể thêm logic phân quyền nếu cần
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'id_category' => 'required|integer|exists:sub_categories,id',
            'id_brand' => 'required|integer|exists:brands,id',
            'id_discount' => 'nullable|exists:discounts,id',
            'status' => 'required|boolean',
            'import_date' => 'nullable|date',
            'hot_product' => 'required|boolean',
            'description' => 'required|string',
            'variants' => 'array',
            'variants.option.*' => 'required|string',
            'variants.stock.*' => 'required|integer|min:0',
            'variants.price.*' => 'required|numeric|min:0',
            'variants.image.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'attributes' => 'array',
            'attributes.sort.*' => 'required|integer',
            'attributes.sort.*' => 'required|integer',
            'attributes.key.*' => 'nullable|string|max:255',
            'attributes.value.*' => 'nullable|string|max:255',
            'img_products' => 'array',
            'img_products.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'customer_segments' => 'nullable|array|min:1',
            'variants.option.*' => 'required|string|max:255',
            'variants.price.*' => 'required|numeric|min:0',
            'variants.stock.*' => 'required|integer|min:0',
            'variants.image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1048',
        ];
    }


    /**
     * Custom message cho các rule
     */
    public function messages(): array
    {
        $message = 'Vui lòng kiểm tra lại thông tin các biến thể (tên, giá, tồn kho, hoặc hình ảnh).';

        return [
            'name.required' => 'Vui lòng tên sản phẩm không được để trống.',
            'id_category.required' => 'Vui lòng chọn danh mục.',
            'id_category.exists' => 'Danh mục không tồn tại.',
            'id_brand.required' => 'Vui lòng chọn thương hiệu.',
            'id_brand.exists' => 'Thương hiệu không tồn tại.',
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'description.required' => 'Mô tả không được để trống.',
            'product_image.image' => 'File tải lên phải là hình ảnh.',
            'product_image.mimes' => 'Hình ảnh chỉ hỗ trợ định dạng: jpeg, png, jpg, gif, svg.',
            'product_image.max' => 'Hình ảnh không được vượt quá 2MB.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'hot_product.required' => 'Vui lòng chọn sản phẩm nổi bật.',
            'hot_product.in' => 'Giá trị sản phẩm nổi bật không hợp lệ.',
            'import_date.required' => 'Ngày không được để trống.',
            'import_date.date' => 'Ngày nhập không đúng định dạng.',
            'discount_value.numeric' => 'Giảm giá phải là số.',
            'discount_value.min' => 'Giảm giá không được nhỏ hơn 0.',
            'discount_value.max' => 'Giảm giá không được lớn hơn 100.',
            'attributes.key.*.string' => 'Thuộc tính phải là chuỗi ký tự.',
            'attributes.key.*.max' => 'Thuộc tính không được dài quá 255 ký tự.',
            'attributes.value.*.string' => 'Giá trị thuộc tính phải là chuỗi ký tự.',
            'attributes.value.*.max' => 'Giá trị thuộc tính không được dài quá 255 ký tự.',
            'customer_segments.required' => 'Vui lòng chọn ít nhất một đối tượng.',
            'customer_segments.array'    => 'Định dạng đối tượng không hợp lệ.',
            'customer_segments.min'      => 'Phải chọn ít nhất một đối tượng.',
            'attributes.key.*.required' => 'Vui lòng nhập tên thuộc tính.',
            'attributes.value.*.required' => 'Vui lòng nhập giá trị thuộc tính.',
            'variants.option.*.required' => $message,
            'variants.option.*.string' => $message,
            'variants.option.*.max' => $message,

            'variants.price.*.required' => $message,
            'variants.price.*.numeric' => $message,
            'variants.price.*.min' => $message,

            'variants.stock.*.required' => $message,
            'variants.stock.*.integer' => $message,
            'variants.stock.*.min' => $message,

            'variants.image.*.image' => $message,
            'variants.image.*.mimes' => $message,
            'variants.image.*.max' => $message,
        ];
    }
}
