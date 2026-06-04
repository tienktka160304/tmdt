<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandAdController extends Controller
{
    public function index(Request $request)
    {
        $sotrang = $request->input('show', 10);
        $query = Brand::withCount('products');

        $title = '';
        if($request->has('query')){
            $filter = $request->query('query');
            if($filter === 'hidden'){
                $query->where('brands.status', 0);
                $title ='Thương hiệu ẩn';
            }elseif ($filter === 'deleted') {
                $query = Brand::onlyTrashed()->withCount('products'); 
                $title = 'Danh mục đã xóa';
            }
        }
        if ($key = request()->key) {
            $query->where('name', 'like', "%{$key}%");
        }
        if ($request->has('sort')) {
            if ($request->sort == 'newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($request->sort == 'oldest') {
                $query->orderBy('created_at', 'asc');
            }elseif($request->sort == 'lowest'){
                $query->orderBy('sort', 'desc');
            }elseif($request->sort == 'highest'){
                $query->orderBy('sort', 'asc');
            }
        }
        $brands = $query->paginate($sotrang)->appends([
            'show' => $sotrang,
            'key' => request()->key,
        ]);

        $pageht = $brands->currentPage();
        $lapa = $brands->lastPage();
        $start = max($pageht - 1, 1);
        $end = min($pageht + 1, $lapa);

        return view('page.brand.index', compact('brands', 'start', 'end', 'pageht', 'lapa', 'sotrang'));
    }

    public function create()
    {
        return view('page.brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'sort' => 'nullable|integer',
        ]);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status;
        $brand->sort = $request->sort;

        if ($request->hasFile('logo')) {
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('img/brand'), $logoName);
            $brand->logo = 'img/brand/' . $logoName;
        }
        $brand->save();

        return redirect()->route('page.brand.index')->with('success', 'Thêm thương hiệu thành công !');
    }

    // public function edit($id)
    // {
    //     $brand = Brand::findOrFail($id);
    //     return view('page.brand.edit', compact('brand'));
    // }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'sort' => 'nullable|integer',
        ]);
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);
        $brand->status = $request->status;
        $brand->sort = $request->sort;
        if ($request->hasFile('logo')) {
            if ($brand->logo && file_exists(public_path($brand->logo))) {
                unlink(public_path($brand->logo));
            }
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('img/brand'), $logoName);
            $brand->logo = 'img/brand/' . $logoName;
        }
        $brand->save();

        return redirect()->route('page.brand.index', $id)->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->products()->count() > 0) {
            return redirect()->route('page.brand.index')->with('error', 'Không thể xóa! Thương hiệu này đang chứa sản phẩm.');
        }

        $brand->delete(); // Sử dụng soft delete

        return redirect()->route('page.brand.index')->with('success', 'Xóa thương hiệu thành công!');
    }

    public function restore($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->back()->with('success', 'Khôi phục thương hiệu thành công');
    }

    public function toggleStatus($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->status = !$brand->status;
        $brand->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }
}
