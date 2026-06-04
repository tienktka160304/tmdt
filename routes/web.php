<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerAdController;
use App\Http\Controllers\BrandAdController;
use App\Http\Controllers\CategoryAdController;
use App\Http\Controllers\CommentAdController;
use App\Http\Controllers\DiscountAdController;
use App\Http\Controllers\HomeAdController;
use App\Http\Controllers\OrderAdController;
use App\Http\Controllers\PaymentAdController;
use App\Http\Controllers\PostAdController;
use App\Http\Controllers\PostcateAdController;
use App\Http\Controllers\ProductAdController;
use App\Http\Controllers\UserAdController;
use App\Http\Controllers\UserReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Dashboard + Order
|--------------------------------------------------------------------------
*/

Route::middleware(['roles:2,3'])->group(function () {
    Route::get('/', [HomeAdController::class, 'home'])->name('home');

    Route::get('/donhang', [OrderAdController::class, 'index'])->name('donhang');
    Route::get('/donhang_chitiet/{id}', [OrderAdController::class, 'detai'])->name('donhang_chitiet');
    Route::get('/orders/{id}/edit', [OrderAdController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/status', [OrderAdController::class, 'updateStatus'])->name('orders.updateStatus');
})->name('detail');

Route::get('/edit_hoadon', function () {
    return view('page.order.edit');
})->name('edit');

/*
|--------------------------------------------------------------------------
| Product Management
|--------------------------------------------------------------------------
*/

Route::middleware(['roles:2,3'])->group(function () {
    Route::get('/sanpham', [ProductAdController::class, 'index'])->name('page.product.sanpham');
    Route::get('/sanpham/add', [ProductAdController::class, 'create'])->name('page.product.add');
    Route::post('/sanpham/store', [ProductAdController::class, 'add_data'])->name('page.product.store');

    Route::get('/sanpham/edit/{id}', [ProductAdController::class, 'edit'])->name('page.product.edit');
    Route::put('/sanpham/update/{id}', [ProductAdController::class, 'update'])->name('page.product.update');

    Route::get('/sanpham/deltai/{id}', [ProductAdController::class, 'detail_product'])->name('page.product.detail');

    Route::post('/products/{id}/destroy', [ProductAdController::class, 'destroy'])->name('products.destroy');
    Route::delete('/products/{id}/force-delete', [ProductAdController::class, 'forceDelete'])->name('products.forceDelete');
    Route::post('/sanpham/restore/{id}', [ProductAdController::class, 'restore'])->name('products.restore');

    Route::post('/sanpham/status/{id}', [ProductAdController::class, 'toggleStatus'])->name('product.toggleStatus');
    Route::post('/sanpham/toggle-hot/{id}', [ProductAdController::class, 'toggleHot'])->name('product.toggleHot');

    Route::get('/check-variant-in-orders/{id}', [ProductAdController::class, 'checkVariantInOrders']);
});

/*
|--------------------------------------------------------------------------
| Post Management
|--------------------------------------------------------------------------
*/

Route::middleware(['roles:2,3'])->group(function () {
    Route::get('/baiviet', [PostAdController::class, 'index'])->name('baiviet');
    Route::get('/baiviet/add_post', [PostAdController::class, 'create_post'])->name('page.post.create_post');
    Route::post('/baiviet/add_post', [PostAdController::class, 'add_data_post'])->name('page.post.store');

    Route::get('/baiviet/edit_post/{id}', [PostAdController::class, 'edit_post'])->name('page.post.edit');
    Route::put('/baiviet/update_post/{id}', [PostAdController::class, 'update_post'])->name('page.post.update');

    Route::get('/baiviet/{id}', [PostAdController::class, 'show'])->name('post.show');

    Route::post('/baiviet/{id}/destroy', [PostAdController::class, 'destroy'])->name('post.destroy');
    Route::delete('/baiviet/{id}/force-delete', [PostAdController::class, 'forceDelete'])->name('post.forceDelete');
    Route::post('/baiviet/restore/{id}', [PostAdController::class, 'restore'])->name('baiviet.restore');

    Route::post('/baiviet/toggle-hot/{id}', [PostAdController::class, 'toggleHot'])->name('post.toggleHot');
    Route::post('/baiviet/status/{id}', [PostAdController::class, 'toggleStatus'])->name('post.toggleStatus');

    Route::get('/post-category', [PostcateAdController::class, 'index'])->name('catepost');
    Route::post('/post-category', [PostcateAdController::class, 'store'])->name('category.store');
    Route::put('/post-category/{id}', [PostcateAdController::class, 'update'])->name('category.update');
    Route::delete('/post-category/{id}', [PostcateAdController::class, 'destroy'])->name('category.destroy');
    Route::post('/post-category/restore/{id}', [PostcateAdController::class, 'restore'])->name('category.restore');
    Route::delete('/post-category/force-delete/{id}', [PostcateAdController::class, 'forceDelete'])->name('category.forceDelete');
    Route::post('/post-cate/{id}', [PostcateAdController::class, 'togglestatus'])->name('catepost.togglestatus');
});

/*
|--------------------------------------------------------------------------
| User + Admin Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['roles:2'])->group(function () {
    Route::get('/user', [UserAdController::class, 'index'])->name('user');
    Route::get('/user/detail/{id}', [UserAdController::class, 'detail_us'])->name('dtus');

    Route::get('/user_review', [UserReviewController::class, 'index'])->name('user_review.show');
    Route::post('/user/changeReview', [UserReviewController::class, 'changeStatus'])->name('change.status');

    Route::put('/user/upRole/{id}', [UserAdController::class, 'up_roles'])->name('user.UpRole');
    Route::post('/user/block', [UserAdController::class, 'blockuser'])->name('user.block');

    Route::get('/ad', [UserAdController::class, 'admin'])->name('admin');
    Route::get('/ad/detail_ad/{id}', [UserAdController::class, 'detail_ad'])->name('dtad');
    Route::get('/ad/edit_ad/{id}', [UserAdController::class, 'edit_ad'])->name('edad');
    Route::put('/ad/update_ad/{id}', [UserAdController::class, 'update_ad'])->name('upad');
});

/*
|--------------------------------------------------------------------------
| Payment Management
|--------------------------------------------------------------------------
*/

Route::middleware(['roles:2,3'])->group(function () {
    Route::get('/payment', [PaymentAdController::class, 'index'])->name('payments.index');
    Route::get('/create', [PaymentAdController::class, 'create'])->name('payments.create');
    Route::post('/', [PaymentAdController::class, 'store'])->name('payments.store');
    Route::get('/{id}/edit', [PaymentAdController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{id}', [PaymentAdController::class, 'update'])->name('payments.update');
    Route::put('/payments/{id}/toggle-status', [PaymentAdController::class, 'toggleStatus'])->name('payments.toggleStatus');
});

/*
|--------------------------------------------------------------------------
| Auth Admin
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AuthController::class, 'index'])->name('auth');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/

Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $request->query('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        $frontendUrl = env('FRONTEND_URL');
        $title = 'Đặt lại mật khẩu thành công và bạn có thể đăng nhập vào tài khoản của mình.';

        return view('auth.change-password-success', compact('title', 'frontendUrl'));
    }

    return back()->withErrors([
        'email' => 'Token không hợp lệ hoặc đã hết hạn.',
    ]);
})->name('password.update');

/*
|--------------------------------------------------------------------------
| Comment Management
|--------------------------------------------------------------------------
*/

Route::get('/binhluan', [CommentAdController::class, 'index'])->name('page.comment.binhluan');
Route::post('/binhluan/block/{id}', [CommentAdController::class, 'blockcomment']);
Route::delete('/binhluan/{id}/delete', [CommentAdController::class, 'destroy'])->name('comment.destroy');
Route::put('/binhluan/update-status/{id}', [CommentAdController::class, 'updateStatus'])->name('update.status');

/*
|--------------------------------------------------------------------------
| Brand Management
|--------------------------------------------------------------------------
*/

Route::get('/thuonghieu', [BrandAdController::class, 'index'])->name('page.brand.index');
Route::get('/thuonghieu/create', [BrandAdController::class, 'create'])->name('brand.create');
Route::post('/thuonghieu/store', [BrandAdController::class, 'store'])->name('brand.store');
Route::get('/thuonghieu/edit/{id}', [BrandAdController::class, 'edit'])->name('brand.edit');
Route::post('/thuonghieu/{id}/update', [BrandAdController::class, 'update'])->name('brand.update');
Route::delete('/thuonghieu/{id}/delete', [BrandAdController::class, 'destroy'])->name('brand.destroy');
Route::put('/thuonghieu/restore/{id}', [BrandAdController::class, 'restore'])->name('brand.restore');
Route::post('/thuonghieu/{id}', [BrandAdController::class, 'togglestatus'])->name('brand.togglestatus');

/*
|--------------------------------------------------------------------------
| Banner Management
|--------------------------------------------------------------------------
*/

Route::get('/banner', [BannerAdController::class, 'index'])->name('banner.show');
Route::post('/banner/create', [BannerAdController::class, 'create_banner'])->name('banner.create');
Route::post('/banner/edit/{id}', [BannerAdController::class, 'update_banner'])->name('banner.update');
Route::delete('/banner/{id}', [BannerAdController::class, 'destroy'])->name('banner.delete');
Route::patch('/banner/restore/{id}', [BannerAdController::class, 'restore'])->name('banner.restore');

/*
|--------------------------------------------------------------------------
| Admin Profile
|--------------------------------------------------------------------------
*/

Route::get('/pro-file', [UserAdController::class, 'profile_ad'])->name('profile');
Route::get('/pro-file-edit', [UserAdController::class, 'up_profile_ad'])->name('profile.edit');
Route::put('/pro-file-update', [UserAdController::class, 'str_profile_ad'])->name('profile.up');

Route::get('/changePass', [UserAdController::class, 'ShowchangePass'])->name('changePass.show');
Route::post('/changePass', [UserAdController::class, 'changePass'])->name('changePass');

/*
|--------------------------------------------------------------------------
| Category Management
|--------------------------------------------------------------------------
*/

Route::get('/danhmuc', [CategoryAdController::class, 'index'])->name('page.category.index');
Route::get('/danhmuc/create', [CategoryAdController::class, 'create'])->name('category.create');
Route::post('/danhmuc/store', [CategoryAdController::class, 'store'])->name('categorysp.store');

Route::get('/danhmuc/{id}', [CategoryAdController::class, 'show'])->name('category.show');
Route::post('/danhmuc', [CategoryAdController::class, 'storeSub'])->name('subcategory.store');
Route::put('/danhmuc/sub-cate/{id}', [CategoryAdController::class, 'updateSub'])->name('subcategory.update');
Route::put('/danhmuc/{id}', [CategoryAdController::class, 'update'])->name('category.update');

Route::delete('/danhmuc/{id}', [CategoryAdController::class, 'destroy'])->name('categorysp.destroy');
Route::put('/danhmuc/restore/{id}', [CategoryAdController::class, 'restoreMain'])->name('categoriesMain.restore');

Route::post('/danhmuc/sub-cate/{id}', [CategoryAdController::class, 'togglestatus'])->name('category.togglestatus');
Route::delete('/danhmuc/sub-cate/{id}', [CategoryAdController::class, 'destroySub'])->name('subcate.destroy');
Route::put('/danhmuc/cate-sub/restore/{id}', [CategoryAdController::class, 'restoreSub'])->name('categoriesSub.restore');

/*
|--------------------------------------------------------------------------
| Discount Management
|--------------------------------------------------------------------------
*/

Route::get('/khuyenmai', [DiscountAdController::class, 'index'])->name('khuyenmai');
Route::post('/khuyenmai', [DiscountAdController::class, 'store'])->name('khuyenmai.store');
Route::get('/khuyenmai/create', [DiscountAdController::class, 'create'])->name('khuyenmai.create');
Route::get('/khuyenmai/edit/{id}', [DiscountAdController::class, 'edit'])->name('khuyenmai.edit');
Route::put('/khuyenmai/update/{id}', [DiscountAdController::class, 'update'])->name('khuyenmai.update');
Route::delete('/khuyenmai/delete/{id}', [DiscountAdController::class, 'destroy'])->name('khuyenmai.delete');
Route::post('/khuyenmai/{id}', [DiscountAdController::class, 'togglestatus'])->name('khuyenmai.togglestatus');
Route::post('/khuyenmai/restore/{id}', [DiscountAdController::class, 'restore'])->name('khuyenmai.restore');

/*
|--------------------------------------------------------------------------
| Upload Quill Image
|--------------------------------------------------------------------------
*/

Route::post('/upload-quill-image', function (Request $request) {
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads/quill'), $filename);

        return response()->json([
            'url' => asset('uploads/quill/' . $filename),
        ]);
    }

    return response()->json([
        'error' => 'No file uploaded',
    ], 400);
});