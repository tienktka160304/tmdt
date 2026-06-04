<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\Post_Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostAdController extends Controller
{
  public function index(Request $request)
  {
    $sotrang = $request->input('show', 10);
    $postQuery = Post::query()->join('post_categories', 'posts.id_category', '=', 'post_categories.id')
      ->where('post_categories.status', 1)
      ->select('posts.*');

    $title = '';

    if ($request->has('query')) {
      $filter = $request->query('query');
      if ($filter === 'hot') {
        $postQuery->where('posts.hot', 1);
        $title = 'Bài viết nổi bật';
      } elseif ($filter === 'hidden') {
        $postQuery->where('posts.status', 0);
        $title = 'Bài viết ẩn';
      } elseif ($filter === 'deleted') {
        $postQuery = Post::onlyTrashed()
          ->join('post_categories', 'posts.id_category', '=', 'post_categories.id')
          ->where('post_categories.status', 1)
          ->select('posts.*');
        $title = 'Bài viết đã xóa';
      }
    }

    // Lọc theo danh mục
    if ($categoryId = $request->category_id) {
      $postQuery->where('posts.id_category', $categoryId);
    }

    if ($key = request()->key) {
      $postQuery->where('posts.title', 'like', "%{$key}%");
    }

    if ($request->has('sort')) {
      if ($request->sort == 'newest') {
        $postQuery->orderBy('published_date', 'desc');
      } elseif ($request->sort == 'oldest') {
        $postQuery->orderBy('published_date', 'asc');
      } elseif ($request->sort == 'view_asc') {
        $postQuery->orderBy('views', 'asc');
      } elseif ($request->sort == 'view_desc') {
        $postQuery->orderBy('views', 'desc');
      }
    }

    $post = $postQuery->paginate($sotrang)->appends([
      'show' => $sotrang,
      'key' => request()->key,
    ]);

    $pageht = $post->currentPage();
    $lapa = $post->lastPage();
    $start = max($pageht - 1, 1);
    $end = min($pageht + 1, $lapa);

    $categories = Post_Category::all();

    return view('page.posts.post', compact('post', 'start', 'end', 'pageht', 'lapa', 'sotrang', 'title', 'categories'));
  }

  public function toggleHot($id)
  {
    $post = Post::findOrFail($id);
    $post->hot = $post->hot == 1 ? 0 : 1;
    $post->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái nổi bật thành công!');
  }

  public function show($id)
  {
    $post = Post::with(['user', 'post_category'])->findOrFail($id);
    return view('page.posts.detail', compact('post'));
  }

  public function create_post()
  {
    $user = User::where('roles', '2');
    $postCategories = Post_Category::all();
    return view('page.posts.create', compact('postCategories', 'user'));
  }

  public function add_data_post(PostRequest $request)
  {
    $validated = $request->validate([
      'id_category' => 'required|exists:post_categories,id',
      'title' => 'required|string|max:255',
      'short_description' => 'required|string|max:500',
      'status' => 'required|in:0,1',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
      'content' => 'required',
    ]);

    $slug = Str::slug($request->title);
    // dd($request->all());
    try {
      // Tạo slug từ tiêu đề
      $slug = Str::slug($request->title);


      // Khởi tạo bài viết
      $post = Post::create([
        'id_user' => Auth::user()->id,
        'id_category' => $request->id_category,
        'title' => $request->title,
        'short_description' => $request->short_description, // Đảm bảo trường này được sử dụng đúng
        'status' => $request->status ?? '1',  // Nếu không có status, mặc định là '1'
        'content' => $request->content,
        'published_date' => $request->published_date ?? now(),
        'hot' => $request->hot ?? 0,
        'slug' => $slug,
      ]);

      // Xử lý upload ảnh
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('img/post');
        if (!file_exists($destinationPath)) {
          mkdir($destinationPath, 0777, true);
        }

        $image->move($destinationPath, $imageName);
        $post->image = '/img/post/' . $imageName;
      }

      // Lưu bài viết lần đầu để lấy ID
      $post->save();

      // Cập nhật slug với ID
      $post->slug = $slug . '-' . $post->id;
      $post->save();

      return redirect()->route('baiviet')->with('success', 'Bài viết đã được thêm thành công.');
    } catch (\Exception $e) {
      return redirect()->route('page.post.create_post')->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }
  }



  public function edit_post($id)
  {
    $post = Post::findOrFail($id);
    $user = User::where('roles', '2')->get();  /// 
    $postCategories = Post_Category::all();
    return view('page.posts.edit', compact('post', 'postCategories', 'user'));
  }

  public function update_post(PostRequest $request, $id)
  {
    $post = Post::findOrFail($id);
    $post->id_category = $request->id_category;
    $post->title = $request->title;
    $post->short_description = $request->short_description;
    $post->status = $request->status;
    $post->content = $request->content;
    $post->published_date = $request->published_date;
    $post->hot = $request->hot ?? 0;
    // Cập nhật slug nếu tiêu đề thay đổi
    if ($post->title !== $request->title) {
      $slug = Str::slug($request->title);
      $count = Post::where('slug', 'LIKE', "$slug%")->where('id', '!=', $id)->count();
      if ($count > 0) {
        $slug = $slug . '-' . ($count + 1);
      }
      $post->slug = $slug;
    }

    // Xử lý ảnh
    if ($request->hasFile('image')) {
      // Xóa ảnh cũ nếu có
      if ($post->image) {
        $oldImagePath = public_path($post->image);
        if (file_exists($oldImagePath)) {
          unlink($oldImagePath);
        }
      }
      // Lưu ảnh mới
      $image = $request->file('image');
      $imageName = time() . '.' . $image->getClientOriginalExtension();
      $image->move(public_path('img/post'), $imageName);
      $post->image = '/img/post/' . $imageName;
    }

    $post->save();

    return redirect()->route('baiviet')->with('success', 'Bài viết đã được cập nhật thành công.');
  }




  public function destroy($id)
  {
    $post = Post::findOrFail($id);
    $post->delete(); // Xóa mềm

    return redirect()->route('baiviet')->with('success', 'Sản phẩm đã được xóa mềm.');
  }

  public function restore($id)
  {
    $post = Post::withTrashed()->findOrFail($id);
    $post->restore();

    return redirect()->back()->with('success', 'Khôi phục danh mục thành công');
  }

  // xóa vĩnh viễn
  public function forceDelete($id)
  {
    $product = Post::withTrashed()->findOrFail($id);
    $product->forceDelete(); // Xóa khỏi database

    return redirect()->route('page.posts')->with('success', 'Sản phẩm đã bị xóa vĩnh viễn!');
  }

  public function toggleStatus($id)
  {
    $bv = Post::findOrFail($id);
    $bv->status = $bv->status == 1 ? 0 : 1;
    $bv->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái nổi bật thành công!');
  }
}
