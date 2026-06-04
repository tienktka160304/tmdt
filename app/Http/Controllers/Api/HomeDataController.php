<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\UserReview;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class HomeDataController extends Controller
{
    public function index()
    {
        // Chạy tất cả query song song bằng cách gộp vào 1 request
        // thay vì 10 request riêng lẻ từ frontend

        // 1. Banner position 1 (slider chính)
        $bannerOne = Banner::where('position', 1)
            ->orderBy('id', 'desc')
            ->limit(4)
            ->get();

        // 2. Banner position 2 (banner giữa trang)
        $bannerTwo = Banner::where('position', 2)
            ->orderBy('id', 'desc')
            ->limit(2)
            ->get();

        // 3. Danh mục con
        $subCategories = SubCategory::where('status', '>', 0)
            ->whereHas('products', function ($q) {
                $q->where('status', '>', 0)
                    ->whereHas('brand', fn($q) => $q->where('status', '>', 0));
            })
            ->withCount(['products' => function ($q) {
                $q->where('status', '>', 0)
                    ->whereHas('brand', fn($q) => $q->where('status', '>', 0));
            }])
            ->orderBy('sort', 'asc')
            ->get();

        // 4. Flash Sale
        $flashSale = Discount::where('status', 1)
            ->whereNotNull('time_end')
            ->where('time_start', '<=', now())
            ->orderBy('time_start', 'desc')
            ->first();

        $flashSaleProducts = [];
        if ($flashSale) {
            $flashSaleProducts = Product::where('id_discount', $flashSale->id)
                ->with(['productVariants'])
                ->withWhereHas('brand')
                ->withWhereHas('sub_category')
                ->with(['discount' => fn($q) => $q
                    ->where('time_start', '<=', now())
                    ->where(fn($q) => $q->where('time_end', '>=', now())->orWhereNull('time_end'))
                ])
                ->where('status', '>', 0)
                ->select([
                    'products.*',
                    DB::raw('(SELECT price FROM product_variants WHERE product_variants.id_product = products.id ORDER BY id ASC LIMIT 1) as variant_price'),
                ])
                ->orderBy('import_date', 'desc')
                ->limit(8)
                ->get();
        }

        // 5. Sản phẩm hot (gợi ý cho bạn) - chỉ cần 4
        $hotProducts = Product::with(['productVariants'])
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with(['discount' => fn($q) => $q
                ->where('time_start', '<=', now())
                ->where(fn($q) => $q->where('time_end', '>=', now())->orWhereNull('time_end'))
            ])
            ->select([
                'products.*',
                DB::raw('(SELECT price FROM product_variants WHERE product_variants.id_product = products.id ORDER BY id ASC LIMIT 1) as variant_price'),
            ])
            ->where('status', '>', 0)
            ->where('hot_product', '>', 0)
            ->whereHas('sub_category', fn($q) => $q->where('id_main_category', 1))
            ->orderBy('import_date', 'desc')
            ->limit(4)
            ->get();

        // 6. Sản phẩm bán chạy nhất (laptop) - 8 sản phẩm
        $bestSellingBike = Product::with(['productVariants'])
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with(['discount' => fn($q) => $q
                ->where('time_start', '<=', now())
                ->where(fn($q) => $q->where('time_end', '>=', now())->orWhereNull('time_end'))
            ])
            ->select([
                'products.*',
                DB::raw('(SELECT price FROM product_variants WHERE product_variants.id_product = products.id ORDER BY id ASC LIMIT 1) as variant_price'),
                DB::raw('(SELECT SUM(od.quantity) FROM orders_details od
                          INNER JOIN orders o ON o.id = od.id_order
                          INNER JOIN product_variants pv ON pv.id = od.id_variant
                          WHERE pv.id_product = products.id AND o.status = 3) as total_buy'),
            ])
            ->where('status', '>', 0)
            ->whereHas('sub_category', fn($q) => $q->where('id_main_category', 1))
            ->orderBy('total_buy', 'desc')
            ->limit(8)
            ->get();

        // 7. Sản phẩm bán chạy phụ kiện - 8 sản phẩm
        $bestSellingPhuKien = Product::with(['productVariants'])
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with(['discount' => fn($q) => $q
                ->where('time_start', '<=', now())
                ->where(fn($q) => $q->where('time_end', '>=', now())->orWhereNull('time_end'))
            ])
            ->select([
                'products.*',
                DB::raw('(SELECT price FROM product_variants WHERE product_variants.id_product = products.id ORDER BY id ASC LIMIT 1) as variant_price'),
                DB::raw('(SELECT SUM(od.quantity) FROM orders_details od
                          INNER JOIN orders o ON o.id = od.id_order
                          INNER JOIN product_variants pv ON pv.id = od.id_variant
                          WHERE pv.id_product = products.id AND o.status = 3) as total_buy'),
            ])
            ->where('status', '>', 0)
            ->whereHas('sub_category', fn($q) => $q->where('id_main_category', 3))
            ->orderBy('total_buy', 'desc')
            ->limit(8)
            ->get();

        // 8. Thương hiệu
        $brands = Brand::where('status', '>', 0)
            ->withCount('products')
            ->orderBy('sort', 'asc')
            ->get()
            ->map(function ($brand) {
                if ($brand->logo && !str_starts_with($brand->logo, 'http')) {
                    $brand->logo = asset($brand->logo);
                }
                return $brand;
            });

        // 9. Bài viết mới nhất - 8 bài
        $posts = Post::with(['post_category', 'user'])
            ->orderBy('published_date', 'desc')
            ->limit(8)
            ->get();

        // 10. Đánh giá website
        $reviews = UserReview::with('user')
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return response()->json([
            'status'            => 'success',
            'bannerOne'         => $bannerOne,
            'bannerTwo'         => $bannerTwo,
            'subCategories'     => $subCategories,
            'flashSale'         => $flashSale,
            'flashSaleProducts' => $flashSaleProducts,
            'hotProducts'       => $hotProducts,
            'bestSellingBike'   => $bestSellingBike,
            'bestSellingPhuKien'=> $bestSellingPhuKien,
            'brands'            => $brands,
            'posts'             => ['data' => $posts],
            'reviews'           => ['data' => $reviews],
        ]);
    }
}
