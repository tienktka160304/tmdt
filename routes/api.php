<?php

use App\Http\Controllers\Api\HomeDataController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckOutApiController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CustomerSermentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserReviewController;
use App\Http\Controllers\Api\YeuThichController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API
|--------------------------------------------------------------------------
*/

Route::get('/home-data', [HomeDataController::class, 'index']);

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/forgot-password', [AuthApiController::class, 'sendResetLink']);

Route::get('/comment', [CommentController::class, 'getComment']);
Route::post('/contact', [ContactController::class, 'sendContact']);

Route::get('/productscategory', [ProductController::class, 'getCategory']);
Route::get('/product-relate', [ProductController::class, 'productRelate']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/customersegment', [CustomerSermentController::class, 'getAllcustomer']);
Route::get('/flashSale', [ProductController::class, 'flashSale']);
Route::post('/cartproduct', [ProductController::class, 'cartProduct']);
Route::get('/mainCategoryWithSub', [CategoryController::class, 'mainCategoryWithSub']);

Route::get('/hot-views-post', [PostController::class, 'HotAndViewsPost']);
Route::get('/main-post', [PostController::class, 'mainPostCategory']);
Route::get('/search', [ProductController::class, 'search']);

Route::get('/get-review-web', [UserReviewController::class, 'getReviewWeb']);
Route::get('/payment', [CheckOutApiController::class, 'paymethod']);

Route::post('/sepay-webhook', [CheckOutApiController::class, 'webhook']);
Route::get('/vnpay-return', [CheckOutApiController::class, 'vnpayReturn']);

/*
|--------------------------------------------------------------------------
| Resource API
|--------------------------------------------------------------------------
*/

Route::apiResource('/product', ProductController::class);
Route::apiResource('/category', CategoryController::class);
Route::apiResource('/banner', BannerController::class);
Route::apiResource('/brand', BrandController::class);
Route::apiResource('/post', PostController::class);

/*
|--------------------------------------------------------------------------
| Authenticated API
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::post('/change-password', [AuthApiController::class, 'changePassword']);
    Route::post('/updateUser', [AuthApiController::class, 'updateUser']);
    Route::get('/getUserActive', [AuthApiController::class, 'getUserActive']);

    Route::get('/check-token', function (Request $request) {
        return response()->json([
            'valid' => true,
            'user' => $request->user(),
        ]);
    });

    Route::post('/pulish-comment', [CommentController::class, 'pulishComment']);

    Route::post('/order', [CheckOutApiController::class, 'order']);
    Route::get('/check-out-online', [CheckOutApiController::class, 'getOrderById']);
    Route::post('/kt-thanh-toan', [CheckOutApiController::class, 'KTThanhToan']);

    Route::get('/history-orders', [OrderController::class, 'index']);
    Route::get('/history-orders/{id}', [OrderController::class, 'show']);
    Route::get('/cancel-order/{id}', [OrderController::class, 'cancelOrder']);

    Route::post('/post-review-web', [UserReviewController::class, 'postReviewWeb']);

    Route::get('/favorite/list', [YeuThichController::class, 'postYeuThich']);
    Route::post('/favorite/add', [YeuThichController::class, 'addFavorite']);
    Route::delete('/favorite/remove', [YeuThichController::class, 'removeFavorite']);
});

/*
|--------------------------------------------------------------------------
| Email Verification
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::find($id);

    if (!$user) {
        return response()->json([
            'message' => 'Người dùng không tồn tại.',
        ], 404);
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    $title = $user->hasVerifiedEmail()
        ? 'Email đã được xác nhận thành công.'
        : 'Email đã được xác nhận.';

    $frontendUrl = env('FRONTEND_URL');

    return view('email.verifyEmail', compact('title', 'frontendUrl'));
})->middleware(['signed'])->name('verification.verify');