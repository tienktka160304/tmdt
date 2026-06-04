<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidProduct implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Lấy dữ liệu từ request
        $request = request();
        $importDate = $request->input('import_date');
        $price = $request->input('price');
        $hotProduct = $request->input('hot_product');

        // Kiểm tra price 
        if ($attribute === 'price' && $price < 0) {
            $fail('Giá sản phẩm không được nhỏ hơn 0.');
        }

        if ($attribute === 'price' && $hotProduct == 1 && $price <= 1000) {
            $fail('Giá sản phẩm phải lớn hơn 1000đ khi sản phẩm được chọn là nổi bật.');
        }
        // Kiểm tra thuộc tính sản phẩm (attributes)
        if ($attribute === 'attributes.key') {
            $keys = $request->input('attributes.key', []);
            $values = $request->input('attributes.value', []);

            foreach ($keys as $index => $key) {
                if (!empty($key) && empty($values[$index])) {
                    $fail("Vui lòng nhập giá trị cho thuộc tính tại dòng " . ($index + 1) . ".");
                }
                if (!empty($values[$index]) && empty($key)) {
                    $fail("Vui lòng nhập tên thuộc tính tại dòng " . ($index + 1) . ".");
                }
            }
        }
    }
}
