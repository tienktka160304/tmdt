<?php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CategoryAdController extends Controller
{
    public function index(Request $request)
    {
        $categories = MainCategory::with(['sub_category' => function($query) {
            $query->withCount('products'); 
        }])->get();
        
        $subDeleted = collect();
        $subHidden = collect();

        if($request->has('query')){
            $filter = $request->query('query');
            if($filter === 'deleted'){
                $categories = MainCategory::onlyTrashed()->get();
            }elseif ($filter === 'deleted-sub') {
                $subDeleted = SubCategory::onlyTrashed()->withCount('products')->get();
            }elseif($filter === 'sub-hidden'){
                $subHidden = SubCategory::where('status', 0)->withCount('products')->get();
            }
        }

        return view('page.category.index', compact('categories','subDeleted', 'subHidden'));

    }

    public function create()
    {
        return view('page.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:main_categories,name',
        ]);

        MainCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('page.category.index')->with('success', 'Danh mục đã được thêm thành công!');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:main_categories,name,' . $id,
        ]);

        $category = MainCategory::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('page.category.index')->with('success', 'Danh mục đã được cập nhật!');
    }

    public function destroy($id)
    {
        $category = MainCategory::withCount('sub_category')->findOrFail($id);

        if ($category->sub_category_count > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục có chứa danh mục con.');
        }

        $category->delete();

        return redirect()->route('page.category.index')->with('success', 'Danh mục đã được xóa thành công.');
    }

    public function restoreMain($id)
    {
        $category = MainCategory::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->back()->with('success', 'Khôi phục danh mục thành công');
    }

    public function show($id)
    {
        $category = MainCategory::with('sub_category')->findOrFail($id);
        return view('page.category.detail', compact('category'));
    }

    //thêm danh mục phụ
    public function storeSub(Request $request)
    {
        $request->validate([
            'id_main_category' => 'required|exists:main_categories,id',
            'name' => 'required|string|max:255',
            'sort' => 'required|integer',
            'status' => 'required|boolean',
        ]);
    
        // Tạo slug không trùng
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        // while (\App\Models\SubCategory::where('slug', $slug)->exists()) {
        //     $slug = $originalSlug . '-' . $counter++;
        // }
        while (\App\Models\SubCategory::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
    
        // Tạo danh mục phụ
        \App\Models\SubCategory::create([
            'id_main_category' => $request->id_main_category,
            'name' => $request->name,
            'slug' => $slug,
            'sort' => $request->sort,
            'status' => $request->status,
        ]);
    
        return redirect()->back()->with('success', 'Thêm danh mục phụ thành công!');
    }

    // Sửa danh mục phụ
    public function updateSub(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'sort' => 'required|integer',
        'status' => 'required|boolean', 
    ]);

    $sub = SubCategory::findOrFail($id);
    $sub->name = $request->name;
    $sub->sort = $request->sort;
    $sub->status = $request->status;

    $sub->save();

    return redirect()->route('page.category.index')->with('success', 'Danh mục phụ đã được cập nhật!');
}

    public function toggleStatus($id)
    {
        $sub = SubCategory::findOrFail($id);
        $sub->status = !$sub->status;
        $sub->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    public function destroySub($id)
    {
        $sub = SubCategory::findOrFail($id);
        if ($sub->products()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa danh mục phụ vì đang có sản phẩm liên kết.');
        }
        $sub->delete();
        //$sub->forceDelete(); // Đây là xóa vĩnh viễn khỏi database
    
        return redirect()->back()->with('success', 'Xóa danh mục phụ thành công.');
    }

    public function restoreSub($id)
    {
        $sub= SubCategory::withTrashed()->findOrFail($id);
        $sub->restore();

        return redirect()->back()->with('success', 'Khôi phục danh mục thành công');
    }
}
