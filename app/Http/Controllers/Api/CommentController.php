<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
  public function getComment(Request $request)
  {

    $comments = Comment::where('id_product', $request->id)->where('status', 1)->with('user')->orderByDesc('created_at')->get();
    return response()->json([
      'status' => 'success',
      'data' => $comments,
    ]);
  }

  public function pulishComment(Request $request)
  {
    $request->validate([
      'id_product' => 'required|exists:products,id',
      'content' => 'required|string|max:1000',
    ]);

    $comment = Comment::create([
      'id_user' => Auth::id(),
      'id_product' => $request->id_product,
      'content' => $request->content,
      'status' => 1,
    ]);

    return response()->json([
      'message' => 'Bình luận thành công',
      'data' => $comment,
    ], 201);
  }
}