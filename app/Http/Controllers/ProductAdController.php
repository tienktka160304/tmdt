<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Models\Attribute_product;
use App\Models\Brand;
use App\Models\CustomerSerment;
use App\Models\Discount;
use App\Models\Img_product;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;



class ProductAdController extends Controller
{
  public function index(Request $request)
  {
    $sotrang = $request->input('show', 10);
    $title = '';
    $key = $request->key;
    $categoryId = $request->category_id;
    $brandId = $request->brand_id;
    $filter = $request->query('query');
    $sort = $request->filter;

    // Query gốc (loại bỏ join)
    $query = Product::with(['productVariants', 'discount', 'brand', 'sub_category'])
        ->whereHas('brand', fn($q) => $q->where('status', 1))
        ->whereHas('sub_category', fn($q) => $q->where('status', 1));

    // Trạng thái sản phẩm
    if ($filter === 'deleted') {
        $title = 'Sản phẩm xóa';
        $query = Product::onlyTrashed()->with(['productVariants', 'discount', 'brand', 'sub_category']);
    } elseif ($filter === 'hidden') {
        $query = Product::with(['productVariants', 'discount', 'brand', 'sub_category'])
            ->where(function ($q) {
                $q->where('status', 0)
                  ->orWhereHas('sub_category', fn($q2) => $q2->where('status', 0));
            });
    } elseif ($filter === 'hot') {
        $query->where('hot_product', 1);
        $title = 'Hot';
    } elseif ($filter === 'sale') {
        $query->whereHas('discount', fn($q) => $q->where('value', '>', 0));
        $title = 'Khuyến mãi';
    }

    // Lọc theo danh mục
    if ($categoryId) {
        $query->where('id_category', $categoryId);
    }

    // Lọc theo thương hiệu
    if ($brandId) {
        $query->where('id_brand', $brandId);
    }

    // Tìm kiếm
    if ($key) {
        $query->where('name', 'like', "%{$key}%");
    }

    // Lấy toàn bộ để xử lý sort và phân trang thủ công
    $products = $query->get();

    // Gán thông tin phụ: giá, ảnh, khuyến mãi
    foreach ($products as $product) {
        $product->total_stock = $product->productVariants->sum('stock');
        $product->min_price = $product->productVariants->min('price') ?? 0;
        $product->max_price = $product->productVariants->max('price') ?? 0;
        $product->same_price = $product->min_price === $product->max_price;
        $product->image = optional($product->productVariants->first())->image;

        if ($product->discount) {
            $now = now();
            $product->discount_status = $now < $product->discount->time_start ? 'upcoming'
                : ($now->between($product->discount->time_start, $product->discount->time_end ?? $now)
                    ? 'active' : 'expired');
            $product->discount_value = $product->discount->value ?? 0;
        } else {
            $product->discount_status = 'none';
            $product->discount_value = 0;
        }
    }

    // Sắp xếp
    if ($sort === 'price_asc') {
        $products = $products->sortBy('min_price')->values();
    } elseif ($sort === 'price_desc') {
        $products = $products->sortByDesc('min_price')->values();
    } elseif ($sort === 'date_newest') {
        $products = $products->sortByDesc('import_date')->values();
    } elseif ($sort === 'date_oldest') {
        $products = $products->sortBy('import_date')->values();
    }

    // Phân trang thủ công
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $paged = $products->slice(($currentPage - 1) * $sotrang, $sotrang)->values();
    $products = new LengthAwarePaginator($paged, $products->count(), $sotrang);
    $products->setPath($request->url());
    $products->appends($request->all());

    $pageht = $products->currentPage();
    $lapa = $products->lastPage();
    $start = max($pageht - 1, 1);
    $end = min($pageht + 1, $lapa);

    $notFoundMessage = null;
    if ($products->total() === 0 && ($key || $filter || $categoryId || $brandId)) {
        $notFoundMessage = 'Không tìm thấy sản phẩm.';
    }

    $categories = SubCategory::where('status', 1)->get();
    $brands = Brand::where('status', 1)->get();

    return view('page.product.sanpham', compact(
        'products', 'title', 'categories', 'brands',
        'notFoundMessage', 'start', 'end', 'pageht', 'lapa', 'sotrang'
    ));
  }


  public function create()
  {
    $categories = SubCategory::all();
    $brands = Brand::all();
    $customersegments = CustomerSerment::all();
    $discounts = Discount::all();
    $variants = ProductVariant::all();

    return view('page.product.create', compact('categories', 'brands', 'customersegments', 'discounts', 'variants'));
  }

  public function add_data(ProductRequest $request)
  {
    $importDate = $request->import_date ?? now();
    DB::beginTransaction();
    try {
      $slug = Str::slug($request->name);
      $product = Product::create([
        'name' => $request->name,
        'id_category' => $request->id_category,
        'id_brand' => $request->id_brand,
        'id_discount' => $request->id_discount,
        'description' => $request->description,
        'status' => $request->status,
        'import_date' => $importDate,
        'hot_product' => $request->hot_product,
        'slug' => $slug,
      ]);

      $product->update(['slug' => $slug . '-' . $product->id]);

      if ($request->has('variants')) {
        foreach ($request->variants['option'] as $index => $option) {
          $productVariant = new ProductVariant([
            'id_product' => $product->id,
            'option' => $option,
            'stock' => $request->variants['stock'][$index] ?? 0,
            'price' => $request->variants['price'][$index] ?? 0,
          ]);

          if (!empty($request->variants['image'][$index]) && $request->variants['image'][$index]->isValid()) {
            $image = $request->variants['image'][$index];
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img/product/product_variants/'), $imageName);
            $productVariant->image = '/img/product/product_variants/' . $imageName;
          }

          $productVariant->save();
        }
      }

      if ($request->has('attributes')) {
        foreach ($request->input('attributes.key', []) as $index => $key) {
          if (!empty($key) && !empty($request->input('attributes.value')[$index])) {
            Attribute_product::create([
              'id_product' => $product->id,
              'key' => $key,
              'value' => $request->input('attributes.value')[$index],
            ]);
          }
        }
      }

      if ($request->hasFile('img_products')) {
        foreach ($request->file('img_products') as $image) {
          $imageName = time() . '_' . $image->getClientOriginalName();
          $image->move(public_path('img/product'), $imageName);
          Img_product::create([
            'id_product' => $product->id,
            'img_products' => 'img/product/' . $imageName,
          ]);
        }
      }

      if ($request->has('customer_segments')) {
        $product->customerSegments()->sync($request->customer_segments);
      }

      DB::commit();
      return redirect()->route('page.product.sanpham')->with('success', 'Sản phẩm đã được thêm thành công.');
    } catch (\Exception $e) {
      DB::rollBack();
      return redirect()->back()->with('error', 'Lỗi khi thêm sản phẩm: ' . $e->getMessage());
    }
  }


  public function edit($id)
  {
    // Lấy sản phẩm cùng với các biến thể liên quan
    $product = Product::with('attributes', 'productVariants', 'images')->findOrFail($id);
    $categories = SubCategory::all();
    $brands = Brand::all();
    $customersegments = CustomerSerment::all();
    $discounts = Discount::all();

    return view('page.product.edit', compact('product', 'categories', 'brands', 'customersegments', 'discounts'));
  }


  // edit sản phẩm 
  public function update(ProductRequest $request, $id)
  {
    $product = Product::findOrFail($id);
    // Cập nhật slug nếu tên sản phẩm thay đổi
    if ($request->name !== $product->name) {
      $slug = Str::slug($request->name);
      // code mới
      $count = Product::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $id)->count();
      $product->slug = $count ? "{$slug}-{$count}" : $slug;
    }

    // xóa hình ảnh trên data
    $deletedImageIds = $request->input('deleted_images');

    if (!empty($deletedImageIds)) {
      $deletedImageIdsArray = explode(',', $deletedImageIds);
      DB::beginTransaction();
      try {
        // Lấy danh sách đường dẫn ảnh
        $imagePaths = Img_product::whereIn('id', $deletedImageIdsArray)
          ->pluck('img_products')
          ->toArray();

        foreach ($imagePaths as $path) {
          $fullPath = public_path($path);

          // Kiểm tra nếu file tồn tại rồi mới xóa
          if (file_exists($fullPath) && str_starts_with(realpath($fullPath), public_path('img/product'))) {
            @unlink($fullPath);
          }
        }
        // Xóa ảnh trong database
        Img_product::whereIn('id', $deletedImageIdsArray)->delete();
        DB::commit();
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Lỗi khi xóa ảnh: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Lỗi khi xóa ảnh: ' . $e->getMessage());
      }
    }
    // Xóa thuộc tính sản phẩm
    $deletedAttributeIds = $request->input('deleted_attributes');

    if (!empty($deletedAttributeIds)) {
      $deletedAttributeIdsArray = explode(',', $deletedAttributeIds);

      try {
        DB::beginTransaction();

        // Xóa thuộc tính trong database
        Attribute_product::whereIn('id', $deletedAttributeIdsArray)->delete();

        DB::commit();
      } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Lỗi khi xóa thuộc tính: ' . $e->getMessage());
        return redirect()->back()->with('success', 'Cập nhật thành công!');
      }
    }

    // code moi
    if ($request->filled('deleted_variants')) {
      $deletedIds = explode(',', $request->deleted_variants);

      foreach ($deletedIds as $variantId) {
        // Kiểm tra có đơn hàng nào chứa biến thể này không
        $orderIds = OrderDetail::where('id_variant', $variantId)
          ->pluck('id_order')
          ->toArray();

        if (!empty($orderIds)) {
          $orderList = implode(', ', $orderIds);
          return back()->with('error', "Không thể xóa biến thể ID {$variantId} vì đã nằm trong đơn hàng: {$orderList}");
        }

        // Xóa nếu không có đơn hàng liên quan
        ProductVariant::where('id', $variantId)->delete();
      }
    }

    // 

    // Cập nhật thông tin sản phẩm
    $product->update($request->only([
      'name',
      'id_category',
      'id_brand',
      'id_discount',
      'description',
      'status',
      'import_date',
      'hot_product'
    ]));

    // Cập nhật biến thể sản phẩm
    if ($request->has('variants')) {
      $variantsData = $request->variants;
      foreach ($variantsData['option'] as $index => $option) {
        // Lấy ID nếu có, nếu không tạo mới
        $variantId = $variantsData['id'][$index] ?? null;
        $variant = $variantId ? ProductVariant::findOrNew($variantId) : new ProductVariant();
        // Gán dữ liệu chung
        $variant->id_product = $product->id;
        $variant->option   = $option;
        // Nếu không có dữ liệu mới, giữ nguyên giá trị cũ
        $variant->stock    = $variantsData['stock'][$index] ?? ($variant->stock ?? 0);
        $variant->price    = $variantsData['price'][$index] ?? ($variant->price ?? 0);
        // Cập nhật ảnh nếu có file mới
        if (isset($variantsData['image'][$index]) && $variantsData['image'][$index]->isValid()) {
          // Xóa ảnh cũ nếu có
          if ($variant->image && file_exists(public_path($variant->image))) {
            unlink(public_path($variant->image));
          }
          $image = $variantsData['image'][$index];
          $imageName = time() . '_' . $image->getClientOriginalName();
          $image->move(public_path('img/product/product_variants'), $imageName);
          $variant->image = '/img/product/product_variants/' . $imageName;
        }
        $variant->save();
      }
    }

    // Cập nhật thuộc tính sản phẩm (chỉ update hoặc tạo mới, không xóa các thuộc tính không được gửi)
    if ($request->has('attributes')) {
      foreach ($request->input('attributes.id', []) as $index => $id) {
        $data = [
          'key'   => $request->input("attributes.key.$index"),
          'value' => $request->input("attributes.value.$index"),
        ];

        if (!empty($id) && Attribute_product::where('id', $id)->exists()) {
          // Nếu có ID -> cập nhật thuộc tính
          Attribute_product::where('id', $id)->update($data);
        } elseif (!empty($data['key']) && !empty($data['value'])) {
          // Nếu không có ID -> tạo mới thuộc tính
          $data['id_product'] = $product->id;
          Attribute_product::create($data);
        }
      }
    }


    // Cập nhật hình ảnh sản phẩm
    if ($request->hasFile('img_products')) {
      // Lấy mảng ID của ảnh hiện có từ form (nếu có)
      $imgIds = $request->input('img_products_id', []);
      $files = $request->file('img_products');

      foreach ($files as $index => $file) {
        if ($file->isValid()) {
          $imageName = time() . '_' . $file->getClientOriginalName();
          $file->move(public_path('img/product'), $imageName);
          // Nếu có ID tương ứng, cập nhật ảnh đó
          if (isset($imgIds[$index]) && !empty($imgIds[$index])) {
            $imgRecord = Img_product::find($imgIds[$index]);
            if ($imgRecord) {
              $imgRecord->update([
                'img_products' => 'img/product/' . $imageName
              ]);
            }
          } else {
            // Nếu không có ID -> tạo mới ảnh
            Img_product::create([
              'id_product'   => $product->id,
              'img_products' => 'img/product/' . $imageName
            ]);
          }
        }
      }
    }
    // Cập nhật đối tượng
    $product->customerSegments()->sync($request->customer_segments ?? []);

    return redirect()->route('page.product.sanpham')->with('success', 'Sản phẩm đã được cập nhật thành công.');
  }




  // chi tiết sản phẩm
  public function detail_product($id)
  {
    $product = Product::with([
      'attributes',
      'images' => function ($query) {
        $query->orderBy('sort', 'asc')->limit(9);
      },
      'productVariants',
      'sub_category',
      'brand',
      'discount',
      'customerSegments'
    ])->findOrFail($id);

    return view('page.product.detail', compact('product'));
  }


  // xóa mềm
  public function destroy($id)
  {
    $product = Product::findOrFail($id);
    $orderCount = OrderDetail::whereIn('id_variant', $product->productVariants->pluck('id'))->count();

    if ($orderCount > 0) {
      return redirect()->back()->with('error', 'Không thể xóa sản phẩm vì sản phẩm đang có trong đơn hàng.');
    }
    $product->delete(); // Xóa mềm

    return redirect()->route('page.product.sanpham')->with('success', 'Sản phẩm đã được xóa mềm.');
  }
  // khôi phục san phẩm 
  public function restore($id)
  {
    $product = Product::onlyTrashed()->where('id', $id)->first();
    if ($product) {
      $product->restore(); // Khôi phục sản phẩm
      return redirect()->route('page.product.sanpham')->with('success', 'Sản phẩm đã được khôi phục.');
    }
    return redirect()->back()->with('error', 'Không tìm thấy sản phẩm.');
  }
  // xóa vĩnh viễn
  public function forceDelete($id)
  {
    $product = Product::withTrashed()->findOrFail($id);
    $product->forceDelete(); // Xóa khỏi database

    return redirect()->route('page.product.sanpham')->with('success', 'Sản phẩm đã bị xóa vĩnh viễn!');
  }

  public function toggleStatus($id)
  {
      $sp = Product::findOrFail($id);
      $sp->status = !$sp->status;
      $sp->save();

      return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
  }

  public function toggleHot($id)
  {
    $sp = Product::findOrFail($id);
    $sp->hot_product = $sp->hot_product == 1 ? 0 : 1;
    $sp->save();

    return redirect()->back()->with('success', 'Cập nhật trạng thái nổi bật thành công!');
  }

  public function checkVariantInOrders($id)
  {
      $orderIds = OrderDetail::where('id_variant', $id)->pluck('id_order')->toArray();

      if (!empty($orderIds)) {
          return response()->json([
              'exists' => true,
              'orders' => $orderIds
          ]);
      }

      return response()->json(['exists' => false]);
  }

}