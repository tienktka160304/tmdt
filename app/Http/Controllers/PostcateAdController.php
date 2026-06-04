<?php

namespace App\Http\Controllers;

use App\Models\Post_Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PostcateAdController extends Controller{
public function index(Request $request){
    $sotrang = $request->input('show', 10);
    $cateQuery = Post_Category::query();
    $title = 'Tất cả danh mục';

    if($request->has('query')){
        $filter = $request->query('query');
        if ($filter === 'hot') {
            $cateQuery->where('hot', 1);
            $title = 'Danh mục nổi bật';
        } elseif ($filter === 'hidden') {
            $cateQuery->where('status', 0);
            $title = 'Danh mục ẩn';
        } elseif ($filter === 'deleted') {
            $cateQuery = Post_Category::onlyTrashed(); 
            $title = 'Danh mục đã xóa';
        }
    }

    $categories = $cateQuery->paginate($sotrang);

    return view('page.post-category.catepost', compact('categories', 'title'));
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'sort' => 'nullable|integer',
        'status' => 'required|in:0,1',
    ]);
    $catePost = new Post_Category();
    $catePost->name = $request->name;
    $catePost->slug = Str::slug($request->name);
    $catePost->sort = $request->sort;
    $catePost->status = $request->status;

    $catePost->save();

    return redirect()->route('catepost')->with('success', 'Thêm danh mục thành công');
}

public function update(Request $request, $id)
{
    $catePost = Post_Category::findOrfail($id);
    $request->validate([
        'name' => 'required|string|max:255',
        'sort' => 'nullable|integer',
        'status' => 'required|in:0,1',
    ]);

    $catePost->name = $request->name;
    $catePost->slug = Str::slug($request->name);
    $catePost->sort = $request->sort;
    $catePost->status = $request->status;

    $catePost->save();

    return redirect()->route('catepost')->with('success', 'Cập nhật danh mục thành công');
}

public function destroy($id)
{
    $category = Post_Category::findOrFail($id);

    // Nếu có bài viết liên kết thì không cho xóa
    if ($category->posts()->count() > 0) {
        return redirect()->back()->with('error', 'Không thể xóa danh mục vì đang chứa bài viết.');
    }

    $category->delete();

    return redirect()->back()->with('success', 'Xóa danh mục thành công');
}

public function forceDelete($id)
{
    $category = Post_Category::withTrashed()->findOrFail($id);

    if ($category->posts()->count() > 0) {
        return redirect()->back()->with('error', 'Không thể xóa vĩnh viễn danh mục vì đang chứa bài viết.');
    }

    $category->forceDelete();

    return redirect()->back()->with('success', 'Đã xóa vĩnh viễn danh mục');
}

public function restore($id)
{
    $category = Post_Category::withTrashed()->findOrFail($id);
    $category->restore();

    return redirect()->back()->with('success', 'Khôi phục danh mục thành công');
}

public function toggleStatus($id)
{
    $catepost = Post_Category::findOrFail($id);
    $catepost->status = !$catepost->status;
    $catepost->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
}
}