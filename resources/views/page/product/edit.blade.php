@extends('layout')

@section('title', 'Cập nhật sản phẩm')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
{{-- code moi --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!--  -->
<main>
    <form class="page-main" action="{{ route('page.product.update', $product->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="text-title">
            <div class="product-btn-row">
                <button class="product-button-row" type="submit">cập nhật sản phẩm</button>
            </div>
        </div>
        <div class="product-form-container product-left-col">
            <div class="product-grouped-row">
                {{-- tên sản phẩm --}}
                <div class="product-row" style="min-height:110px;">
                    <label class="label-text" for="product-name">Tên sản phẩm</label>
                    <input class="input-text @error('name') is-invalid @enderror" type="text" id="product-name"
                        name="name" placeholder="Nhập tên sản phẩm" value="{{ old('name', $product->name) }}">
                    @error('name')
                    <span class="baoloi">{{ $message }}</span>
                    @enderror
                </div>
                {{-- hết tên sản phẩm --}}
                {{-- mô tảtả --}}
                <div style="height: 300px; margin-bottom: 100px;">
                    <label class="label-text" for="product-name">Mô tả sản phẩm</label>
                    <input type="hidden" name="description" id="hidden-description"
                        value="{{ old('description', $product->description) }}">
                    <div id="editor"></div>
                </div>
                {{-- hết mô tả --}}
                {{-- danh mục --}}
                <div class="categoris-product" style="min-height: 110px">
                    <label class="label-text" for="product-category">Danh mục</label>
                    <select id="product-category" name="id_category" class="@error('id_category') is-invalid @enderror">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('id_category', $product->id_category) ==
                            $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_category')
                    <span class="baoloi">{{ $message }}</span>
                    @enderror
                </div>
                {{-- hết danh mục --}}
                <div class="product-date-row">
                    {{-- ngày nhập --}}
                    <div style="min-height: 110px">
                        <label class="label-text" for="product-import-date">Ngày nhập</label>
                        <input class="input-text" type="date" id="product-import-date" name="import_date"
                            value="{{ old('import_date', isset($product->import_date) ? \Carbon\Carbon::parse($product->import_date)->format('Y-m-d') : '') }}"
                            required>
                        @error('import_date')
                        <span class="baoloi">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- hết ngày nhập --}}

                    {{-- giảm giá --}}
                    <div>
                        <label class="label-text" for="product-discount">Giảm giá (%)</label>
                        <select id="product-discount" name="id_discount"
                            class="@error('id_discount') is-invalid @enderror">
                            <option value="">Chọn mức giảm giá</option>
                            @foreach($discounts as $discount)
                            <option value="{{ $discount->id }}" {{ old('id_discount',$product->id_discount ?? '') ==
                                $discount->id ? 'selected' : '' }}>
                                {{ $discount->description }} ({{ $discount->value }}%)
                            </option>
                            @endforeach
                        </select>
                        @error('id_discount')
                        <span class="baoloi">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- hết giảm giá --}}
                </div>
                {{-- thuộc tính --}}
                <div>
                    <div class="attribute-row">
                        {{-- input thuộc tính --}}
                        <div id="attribute-container">
                            <label class="label-text">Thuộc tính</label>
                            @foreach($product->attributes as $index => $attribute)
                            <div class="product-input-group ">
                                <!-- Gửi kèm ID của thuộc tính -->
                                <input type="hidden" name="attributes[id][]" value="{{ $attribute->id }}">

                                <div class="attributes-key">
                                    <input id="attribute-key-{{ $index }}"
                                        class="input-text attribute-input @error('attributes.key.'.$index) is-invalid @enderror"
                                        type="text" name="attributes[key][]" placeholder="Nhập thuộc tính sản phẩm"
                                        value="{{ old('attributes.key.'.$index, $attribute->key ?? '') }}">
                                </div>

                                <div class="attributes-value">
                                    <input id="attribute-value-{{ $index }}"
                                        class="input-text value-input @error('attributes.value.'.$index) is-invalid @enderror"
                                        type="text" name="attributes[value][]" placeholder="Giá trị thuộc tính"
                                        value="{{ old('attributes.value.'.$index, $attribute->value ?? '') }}">
                                </div>
                                <div class="delete-container">
                                    <a href="#" type="button" class="delete-attribute" >
                                        Xóa
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="deleted_attributes" id="deleted_attributes">
                        {{-- hết input thuôc tính --}}
                    </div>
                    {{-- nút thêm --}}
                    <div class="plus-container">
                        <i class="fa-solid fa-circle-plus plus-icon-attribute" onclick="addAttributeField()"></i>
                    </div>

                </div>
                {{-- hết thuộc tính --}}
                <div>
                    <div class="product-file-status ">
                        {{-- sản phẩm hot --}}
                        <div class="product-checkbox-container toggle-container">
                            <label class="label-text" for="product-hot">Sản phẩm hot</label>

                            <input type="hidden" name="hot_product" value="0">
                            <input class="input-text @error('hot_product') is-invalid @enderror" type="checkbox"
                                id="product-hot" name="hot_product" value="1" {{ old('hot_product',
                                $product->hot_product) == 1 ? 'checked' : '' }}>

                            <label for="product-hot" class="toggle-btn">🔥 HOT 🔥</label>
                            @error('hot_product')
                            <span class="baoloi">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- hết hothot --}}
                        {{-- trạng thái --}}
                        <div class="toggle-container">
                            <label class="label-text" for="status">Trạng thái</label>
                            <input type="hidden" name="status" value="0">
                            <label class="switch">
                                <input class="input-text @error('status') is-invalid @enderror" type="checkbox"
                                    id="status" name="status" value="1" {{ old('status', $product->status ?? 1) == '1' ?
                                'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                            @error('status')
                            <span class="baoloi">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- hết trạng thái --}}

                    </div>
                    {{-- đối tượng --}}
                    <div>
                        <label class="label-text">Đối tượng</label>
                        <div class="customer_segments-checkbox">
                            @foreach ($customersegments as $segment)
                            <div class="customer-checkbox">
                                <input type="checkbox" id="customer-segment-{{ $segment->id }}"
                                    name="customer_segments[]" value="{{ $segment->id }}" {{ in_array($segment->id,
                                old('customer_segments', $product->customerSegments->pluck('id')->toArray())) ?
                                'checked' : '' }}>
                                <label class="label-text" for="customer-segment-{{ $segment->id }}">{{ $segment->name
                                    }}</label>
                            </div>
                            @endforeach
                            @error('customer_segments')
                            <span class="baoloi">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    {{-- hết đối tượng --}}

                </div>
            </div>

            <div>
                {{--ảnh concon --}}
                <div class="product-upload-status-hot">
                    <label class="label-text">Ảnh sản phẩm</label>
                    {{-- test --}}
                    <div id="image-upload-container">
                        @if(isset($product->images) && count($product->images) > 0)
                        @foreach($product->images as $index => $img)
                        <div class="product-upload-img" id="product-img-{{ $index }}">
                            <div class="box-img-upload">
                                <img class="preview-image" id="preview-image-{{ $index }}"
                                    src="{{ asset($img->img_products) }}" style="width: 100%; height: auto;"
                                    alt="Xem trước ảnh">
                                <i id="preview-icon-{{ $index }}" class="fa-solid fa-image" style="display: none;"></i>

                                <label style="position: absolute; inset: 0; cursor: pointer;"  for="product-upload-{{ $index }}">
                                    <input class="input-text custom__input-file" type="file"
                                        id="product-upload-{{ $index }}" name="img_products[]" accept="image/*"
                                        data-id="{{ $img->id }}"
                                        onchange="editPreviewImages(event, {{ $index }})"
                                        style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                </label>

                                <!-- Nút xóa ảnh -->
                                <button type="button" class="remove-image" id="remove-btn-{{ $index }}"
                                    onclick="editRemoveImage({{ $index }})" style="display: inline-block;">❌</button>
                            </div>
                        </div>
                        @endforeach
                        @endif

                        <!-- Nút "+" để thêm ảnh -->
                        <div class="product-upload-img-i add-more-image" id="add-image-button">
                            <div class="box-img-upload plus-iconn">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Trường ẩn lưu ID ảnh bị xóa -->
                    <input type="hidden" name="deleted_images" id="deleted_images">
                </div>
                {{-- hét ảnh con --}}
                <div>
                    {{-- thuong hiệu--}}
                    <div style="min-height: 110px">
                        <label class="label-text" for="product-brand">Thương hiệu</label>
                        <select id="product-brand" name="id_brand" class="@error('id_brand') is-invalid @enderror">
                            <option value="">Chọn thương hiệu</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('id_brand', $product->id_brand) == $brand->id ?
                                'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('id_brand')
                        <span class="baoloi">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- hết thương hiệu --}}
                    {{-- biến thể --}}
                    <label class="label-text">Biến Thể</label>
                    <div id="variants-product">
                        @foreach($product->productVariants as $index => $variant)
                        <div class="product-variants">
                            <input type="hidden" name="variants[id][]"
                                value="{{ old('variants.id.' . $index, $variant->id) }}">
                            <!-- Cột trái -->
                            <div class="product-variants-left">
                                <input class="input-text" type="text" placeholder="Tên Biến Thể"
                                    name="variants[option][]"
                                    value="{{ old('variants.option.' . $index, $variant->option) }}">

                                <div class="product-variants-row">
                                    <input class="input-text" type="number" placeholder="Nhập số lượng sản phẩm" min="1"
                                        name="variants[stock][]"
                                        value="{{ old('variants.stock.' . $index, $variant->stock) }}"
                                        oninput="validatePrice(this)">
                                    <input class="input-text" type="text" min="1000" placeholder="Nhập giá sản phẩm"
                                        name="variants[price][]"
                                        value="{{ old('variants.price.' . $index, $variant->price) }}"
                                        oninput="validatePrice(this)">
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="product-upload-status-variants">
                                <div class="product-upload-imggg">
                                    <div class="box-img-uploadd">

                                        @if(!empty($variant->image))
                                        <img class="preview-image" src="{{ asset( $variant->image) }}"
                                            style="width: 100%; height: auto;" alt="Xem trước ảnh">
                                        @else

                                        <img class="preview-image" style="display: none; width: 100%; height: auto;"
                                            alt="Xem trước ảnh">
                                        @endif
                                        <label style="position: absolute; inset: 0; cursor: pointer;">
                                            <input class="input-text custom__input-file" type="file"
                                                name="variants[image][]" onchange="previewImage(this)" style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                        </label>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- Nút xóa -->
                            <div class="button-product-variants" style="margin-top: -6px;">
                                <a href="#" type="button" class="delete-variant" data-id="{{ $variant->id }}">Xóa</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="deleted_variants" id="deleted_variants">
                    <div class="button-product-variants">
                        <i class="fa-solid fa-circle-plus plus-icon-variants" onclick="Variants()"></i>
                    </div>
                    {{-- hết biên thểthể --}}
                </div>
            </div>
        </div>
    </form>
</main>

<script src="{{ asset('/js/QuillEditText.js') }}" defer></script>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection