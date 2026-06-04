<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YeuThichController extends Controller
{
  /**
   * Lấy danh sách sản phẩm yêu thích của người dùng
   */
  public function postYeuThich()
  {

    $userId = Auth::id();
    if (!$userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập',
      ], 401);
    }

    $favoriteProductIds = FavoriteProduct::where('id_user', $userId)->pluck('id_product');

    $yeuthich = Product::with([
      'productVariants',
      'brand',
      'sub_category',
      'discount' => function ($query) {
        $query->where('time_start', '<=', now())
          ->where(function ($q) {
            $q->where('time_end', '>=', now())
              ->orWhereNull('time_end');
          });
      }
    ])
      ->withSum(['orderDetails as total_buy' => function ($orderQuery) {
        $orderQuery->whereHas('order', function ($orderQuery) {
          $orderQuery->where('status', 3);
        });
      }], 'quantity')
      ->selectRaw(
        'products.*, 
                (SELECT price FROM product_variants 
                 WHERE product_variants.id_product = products.id 
                 ORDER BY id ASC LIMIT 1) as variant_price'
      )
      ->whereIn('id', $favoriteProductIds) // Lọc sản phẩm theo danh sách yêu thích
      ->where('status', '>', 0)
      ->get();

    return response()->json([
      'status' => 'success',
      'data' => $yeuthich
    ]);
  }


  public function addFavorite(Request $request)
  {
    $userId = Auth::id();
    if (!$userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập',
      ], 401);
    }

    $productId = $request->input('id_product');

    // Kiểm tra sản phẩm có tồn tại không
    if (!Product::where('id', $productId)->exists()) {
      return response()->json([
        'status' => 'error',
        'message' => 'Sản phẩm không tồn tại',
      ], 404);
    }

    // Kiểm tra sản phẩm đã có trong danh sách yêu thích chưa
    $exists = FavoriteProduct::where('id_user', $userId)
      ->where('id_product', $productId)
      ->exists();

    if ($exists) {
      return response()->json([
        'status' => 'error',
        'message' => 'Sản phẩm đã có trong danh sách yêu thích',
      ], 409);
    }

    // Thêm sản phẩm vào danh sách yêu thích
    FavoriteProduct::create([
      'id_user' => $userId,
      'id_product' => $productId
    ]);

    return response()->json([
      'status' => 'success',
      'message' => 'Đã thêm sản phẩm vào danh sách yêu thích',
    ]);
  }


  public function removeFavorite(Request $request)
  {
    $userId = Auth::id();
    if (!$userId) {
      return response()->json([
        'status' => 'error',
        'message' => 'Người dùng chưa đăng nhập',
      ], 401);
    }

    $productId = $request->input('id_product');

    // Kiểm tra sản phẩm có trong danh sách yêu thích không
    $favorite = FavoriteProduct::where('id_user', $userId)
      ->where('id_product', $productId)
      ->first();

    if (!$favorite) {
      return response()->json([
        'status' => 'error',
        'message' => 'Sản phẩm không có trong danh sách yêu thích',
      ], 404);
    }

    // Xóa sản phẩm khỏi danh sách yêu thích
    $favorite->delete();

    return response()->json([
      'status' => 'success',
      'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích',
    ]);
  }
}
