<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Lấy danh sách thương hiệu.
     */
    public function index()
    {
        // 1. Lấy dữ liệu từ Database
        $brands = Brand::where('status', '>', 0)
            ->orderBy('sort', 'asc')
            ->get();

        // 2. Duyệt qua từng thương hiệu để fix đường dẫn ảnh
        // Hàm asset() sẽ tự động lấy APP_URL và thêm dấu / chuẩn xác
        $brands->transform(function ($brand) {
            if ($brand->logo) {
                // Kiểm tra nếu chưa có http thì mới thêm domain và dấu /
                if (!str_starts_with($brand->logo, 'http')) {
                    $brand->logo = asset($brand->logo);
                }
            }
            return $brand;
        });

        // 3. Trả về JSON cho React
        return response()->json([
            'status' => 'success',
            'data' => $brands
        ]);
    }

    /**
     * Các hàm store, show, update, destroy... giữ nguyên cấu trúc cũ
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $brand = Brand::find($id);
        if ($brand && $brand->logo && !str_starts_with($brand->logo, 'http')) {
            $brand->logo = asset($brand->logo);
        }
        
        return response()->json([
            'status' => 'success',
            'data' => $brand
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}