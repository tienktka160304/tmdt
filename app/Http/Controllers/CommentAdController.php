<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Product;

class CommentAdController extends Controller
{
  public function index(Request $request)
  {
    $sotrang = $request->input('show', 10);
    $query = Comment::query();
    $productId = $request->id_product;

    if ($keyword = $request->input('keyword')) {
      $query->where(function ($q) use ($keyword) {
          $q->where('content', 'like', "%{$keyword}%")
            ->orWhereHas('user', function ($q2) use ($keyword) {
                $q2->where('first_name', 'like', "%{$keyword}%")
                   ->orWhere('last_name', 'like', "%{$keyword}%");
            })
            ->orWhereHas('products', function ($q3) use ($keyword) {
                $q3->where('id_product', 'like', "%{$keyword}%");
            });
      });
  }
    if ($request->has('sort')) {
      if ($request->sort == 'newest') {
        $query->orderBy('created_at', 'desc');
      } elseif ($request->sort == 'oldest') {
        $query->orderBy('created_at', 'asc');
      }
    }

    if($productId) {
      $query->where('id_product', $productId);
    }

    $comment = $query->paginate($sotrang)->appends([
      'show' => $sotrang,
      'keyword' =>$keyword,
    ]);
    $pageht = $comment->currentPage();
    $lapa = $comment->lastPage();
    $start = max($pageht - 1, 1);
    $end = min($pageht + 1, $lapa);

    $products = Product::where('status', 1)->get();

    return view('page.comment.binhluan', compact('comment', 'start', 'end', 'pageht', 'lapa', 'sotrang', 'products'));
  }

  public function updateStatus($id)
  {
    $bl = Comment::find($id);
    $bl->status = !$bl->status;
    $bl->save();

    return redirect()->back()->with('success', 'Đã cập nhật trạng thái!');
  }


  public function destroy($id)
  {
    $bl = Comment::findOrFail($id);

    $bl->delete();

    return redirect()->route('page.comment.binhluan')->with('success', 'Xóa bình luận thành công!');
  }
}
