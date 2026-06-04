<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $query = Product::with([
            'productVariants'
        ])

            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with([
                'discount' => function ($query) {
                    $query
                        ->where('time_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('time_end', '>=', now())
                                ->orWhereNull('time_end');
                        });
                }
            ])
            ->select([
                'products.*',
                DB::raw('(SELECT price FROM product_variants 
                          WHERE product_variants.id_product = products.id 
                          ORDER BY id ASC LIMIT 1) as variant_price'),
                DB::raw('(
                    SELECT SUM(orders_details.quantity) FROM orders_details
                    INNER JOIN orders ON orders.id = orders_details.id_order
                    INNER JOIN product_variants ON product_variants.id = orders_details.id_variant
                    WHERE product_variants.id_product = products.id
                    AND orders.status = 3
                ) as total_buy')
            ])
            ->where('status', '>', 0);

        if ($request->has('mainCate')) {
            $query->whereHas('sub_category', function ($q) use ($request) {
                $q->where('id_main_category', '=', $request->mainCate);
            });
        } else {
            $query->whereHas('sub_category', function ($q) use ($request) {
                $q->where('id_main_category', '=', 1);
            });
        }
        // đối tượngtượng
        if ($request->has('dt')) {
            $query->withWhereHas('productCustomerSegments', function ($q) use ($request) {
                $q->where('id_customer_segment', '=', $request->dt);
            });
        }

        if ($request->has('ct')) {
            switch ($request->ct) {
                case 'best-selling':
                    $query->orderBy('total_buy', 'desc');
                    break;
                case 'hot':
                    $query->where('hot_product', ">", "0");
                    break;
                case 'discount-value':
                    $query->whereHas('discount', function ($q) {
                        $q->where('time_start', '<=', now())
                            ->where(function ($subQuery) {
                                $subQuery->where('time_end', '>=', now()) // Giảm giá còn hạn
                                    ->orWhereNull('time_end'); // Hoặc không có ngày kết thúc
                            });
                    });
                    break;
            }
        }

        $query->orderBy('import_date', 'desc');
        $limit = $request->get('limit', 8);
        $data = $query->limit($limit)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }


    /**
     *  
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $product = Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'description' => $request->input('description')
        ]);
        return response()->json([
            'status' => 'success',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $id = Product::where('slug', $slug)->value('id');

        $product = Product::with([
            'productVariants'
        ])->withSum([
                    'orderDetails as total_buy' => function ($orderQuery) {
                        $orderQuery->whereHas('order', function ($orderQuery) {
                            $orderQuery->where('status', 3);
                        });
                    }
                ], 'quantity')
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')->with([
                    'discount' => function ($query) {
                        $query
                            ->where('time_start', '<=', now())
                            ->where(function ($q) {
                                $q->where('time_end', '>=', now())
                                    ->orWhereNull('time_end');
                            });
                    }
                ])
            ->with([
                'productCustomerSegments',
                'images',
                'attribute',
                'comment',
            ])->where('status', '>', 0)
            ->find($id);
        //
        if ($product) {
            $product->increment('views');
            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không có sản phẩm'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $product = Product::find($id);
        if ($product) {
            $product->update([
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'description' => $request->input('description')
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $product
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }


        // // 
        // $post = Post::findOrFail($id);
        // $post->update($request->all());
        // return response()->json($post);
    }


    public function destroy(string $id)
    {
        //
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
    }


    public function getCategory(Request $request)
    {
        //

        $query = Product::with([
            'productVariants'
        ])

            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with([
                'discount' => function ($query) {
                    $query
                        ->where('time_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('time_end', '>=', now())
                                ->orWhereNull('time_end');
                        });
                }
            ])
            ->select([
                'products.*',
                DB::raw('(SELECT price FROM product_variants 
                          WHERE product_variants.id_product = products.id 
                          ORDER BY id ASC LIMIT 1) as variant_price'),
                DB::raw('(
                    SELECT SUM(orders_details.quantity) FROM orders_details
                    INNER JOIN orders ON orders.id = orders_details.id_order
                    INNER JOIN product_variants ON product_variants.id = orders_details.id_variant
                    WHERE product_variants.id_product = products.id
                    AND orders.status = 3
                ) as total_buy')
            ])
            ->where('status', '>', 0);

        if ($request->has('category')) {
            $slugs = explode(',', $request->category);
            $categoryIds = SubCategory::whereIn("slug", $slugs)->pluck('id')->toArray();
            if (!empty($categoryIds)) {
                $query->whereHas('sub_category', function ($q) use ($categoryIds) {
                    $q->whereIn('id_category', $categoryIds);
                });
            }
        }

        // đối tượngtượng
        if ($request->has('dt')) {
            $id = explode(',', $request->dt);
            $query->whereHas('productCustomerSegments', function ($q) use ($id) {
                $q->whereIn('id_customer_segment', $id);
            });
        }

        if ($request->has('brand')) {
            $slug = $request->brand;
            // $brandId = Brand::where("slug", $slug)->first('id');
            $brandId = Brand::where("slug", $slug)->value('id');
            if (!empty($brandId)) {
                $query->whereHas('brand', function ($q) use ($brandId) {
                    $q->where('id_brand', $brandId);
                });
            }
        }

        if ($request->has('ct')) {
            switch ($request->ct) {
                case 'best-selling':
                    $query->orderBy('total_buy', 'desc');
                    break;
                case 'hot':
                    $query->where('hot_product', ">", "0");
                    break;
                case 'discount-value':
                    $query->whereHas('discount', function ($q) {
                        $q->where('time_start', '<=', now())
                            ->where(function ($subQuery) {
                                $subQuery->where('time_end', '>=', now()) // Giảm giá còn hạn
                                    ->orWhereNull('time_end'); // Hoặc không có ngày kết thúc
                            });
                    });
                    break;
            }
        }

        if ($request->filled('pricemin')) {
            $query->having('variant_price', '>=', $request->pricemin);
        }

        if ($request->filled('pricemax')) {
            $query->having('variant_price', '<=', $request->pricemax);
        }

        // // 🔹 Sắp xếp sản phẩm theo các tiêu chí
        if ($request->has('sort')) {
            switch ($request->sort) {
                case '1':
                    $query->orderBy('variant_price', 'desc');
                    break;
                case '2':
                    $query->orderBy('variant_price', 'asc'); //thap den cao
                    break;
            }
        } else {
            $query->orderBy('import_date', 'desc');
        }
        $data = $query->paginate($request->get('limit', 12));
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }


    public function flashSale(Request $request)
    {

        $flashSale = Discount::where('status', 1)
            ->whereNotNull('time_end')
            ->where(function ($query) {
                $query->where('time_start', '<=', now()); // Đã bắt đầu
                // ->where('time_end', '>=', now())
                // Chưa kết thúc
            })
            ->orderBy('time_start', 'desc') // Lấy chương trình mới nhất
            ->first();

        if (!$flashSale) {
            return response()->json([
                'status' => 'error',
                'time' => now(),

                'message' => 'Không có chương trình Flash Sale nào đang chạy'
            ], 404);
        }
        $query = Product::where('id_discount', $flashSale->id)->with([
            'productVariants'
        ])->withSum([
                    'orderDetails as total_buy' => function ($orderQuery) {
                        $orderQuery->whereHas('order', function ($orderQuery) {
                            $orderQuery->where('status', 3);
                        });
                    }
                ], 'quantity')
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with([
                'discount' => function ($query) {
                    $query
                        ->where('time_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('time_end', '>=', now())
                                ->orWhereNull('time_end');
                        });
                }
            ])
            // trạng thái của sản phẩm
            ->where('status', '>', 0);


        $query->orderBy('import_date', 'desc');
        $limit = $request->get('limit', 8);
        $data = $query->limit($limit)->get();
        return response()->json([
            'status' => 'success',
            'sale' => $flashSale,
            'data' => $data,
        ]);
    }


    public function cartProduct(Request $request)
    {

        if (!$request->has('ids') || empty($request->ids) || !is_array($request->ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Giỏ hàng trống',
                'data' => []
            ], 200);
        }

        $products = ProductVariant::with(['product.activeDiscount'])
            ->whereIn('id', $request->ids)
            ->get();
        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm nào trong giỏ hàng',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Lấy danh sách sản phẩm thành công',
            'data' => $products
        ], 200);
    }
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::with([
            'productVariants'
        ])
            ->withSum([
                'orderDetails as total_buy' => function ($orderQuery) {
                    $orderQuery->whereHas('order', function ($orderQuery) {
                        $orderQuery->where('status', 3);
                    });
                }
            ], 'quantity')
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with([
                'discount' => function ($query) {
                    $query
                        ->where('time_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('time_end', '>=', now())
                                ->orWhereNull('time_end');
                        });
                }
            ])
            ->where('status', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->paginate($request->get('limit', 12));

        return response()->json($products);
    }


    public function productRelate(Request $request)
    {
        $products = Product::with([
            'productVariants'
        ])
            ->withSum([
                'orderDetails as total_buy' => function ($orderQuery) {
                    $orderQuery->whereHas('order', function ($orderQuery) {
                        $orderQuery->where('status', 3);
                    });
                }
            ], 'quantity')
            ->withWhereHas('brand')
            ->withWhereHas('sub_category')
            ->with([
                'discount' => function ($query) {
                    $query
                        ->where('time_start', '<=', now())
                        ->where(function ($q) {
                            $q->where('time_end', '>=', now())
                                ->orWhereNull('time_end');
                        });
                }
            ])
            ->where('status', '>', 0)
            ->where('id_category', $request->category)
            ->where('id', '!=', $request->id)
            ->limit(12)
            ->get();

        return response()->json($products);
    }
}