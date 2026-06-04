@extends('layout')

@section('title', 'Thêm sản phẩm')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- text edittor --}}
<main>
    <form class="page-main" action="{{ route('page.product.store') }}" enctype="multipart/form-data" method="POST"
        onsubmit="markFormAsSubmitted()">
        @csrf
        <div class="text-title">
            <div class="product-btn-row">

                <button class="product-button-row" type="submit">
                    <i class="fa-solid fa-circle-plus"></i>
                    Thêm sản phẩm
                </button>
            </div>
        </div>
        <div class="product-form-container product-left-col">
            <div class="product-grouped-row">
                {{-- tên sản phẩm --}}
                <div class="product-row" style="min-height:110px;">
                    <label class="label-text" for="product-name">Tên sản phẩm</label>
                    <input class="input-text @error('name') is-invalid @enderror" type="text" id="product-name"
                        placeholder="Nhập tên sản phẩm" name="name" value="{{ old('name') }}">
                    @error('name')
                    <span class="baoloi">{{ $message }}</span>
                    @enderror
                </div>
                {{-- hết tên sản phẩm --}}
                {{-- mô tảtả --}}
                <div style="height: 300px; margin-bottom: 100px;">
                    <!-- Input ẩn để lưu dữ liệu -->
                    <label class="label-text" for="product-name">Mô tả sản phẩm</label>
                    <input type="hidden" name="description" id="hidden-description"
                        value="{{ old('description', $product->description ?? '') }}">

                    <!-- Quill Editor -->
                    <div id="editor"></div>
                </div>
                {{-- hết mô tả --}}

                {{-- danh mục --}}
                <div class="categoris-product" style="min-height: 110px">
                    <label class="label-text" for="product-category">Danh mục</label>
                    <select id="product-category" name="id_category" class="@error('id_category') is-invalid @enderror">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('id_category') == $category->id ? 'selected' : '' }}>{{
                                $category->name }}</option>
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
                        <input class="input-text @error('import_date') is-invalid @enderror" type="date"
                            id="product-import-date" name="import_date" value="{{ old('import_date', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}">
                        @error('import_date')
                        <span class="baoloi" style="font-size: 14px;">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- hết ngày nhập --}}

                    {{-- giảm giá --}}
                    <div>
                        <label class="label-text" for="product-discount">Giảm giá (%)</label>
                        <select id="product-discount" name="id_discount"
                            class="@error('id_discount') is-invalid @enderror">
                            <option value=""> Không giảm giá</option>
                            @foreach($discounts as $discount)
                            <option value="{{ $discount->id }}" {{ old('id_discount', $product->id_discount ?? '') ==
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
                        <div id="form-add-product">
                            <div id="attribute-container">
                                <label class="label-text">Thuộc tính</label>
                                @foreach (old('attributes.key', ['']) as $index => $oldKey)
                                <div class="product-input-group">
                                    <div class="attributes-key">
                                        <input id="attribute-key-{{ $index }}"
                                            class="input-text attribute-input @error('attributes[key][]') is-invalid @enderror"
                                            type="text" name="attributes[key][]" placeholder="Tên thuộc tính"
                                            value="{{ $oldKey }}">
                                        @error('attributes.key.' . $index)
                                        <div class="baoloi" style="font-size: 13px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="attributes-value">
                                        <input id="attribute-value-{{ $index }}"
                                            class="input-text value-input @error('attributes[value][]') is-invalid @enderror"
                                            type="text" name="attributes[value][]" placeholder="Giá trị thuộc tính"
                                            value="{{ old('attributes.value.' . $index) }}">
                                        @error('attributes.value.' . $index)
                                        <div class="baoloi" style="font-size: 13px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="delete-container">
                                        <a href="" type="button" class="delete-attribute">Xóa</a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
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

                            <input type="checkbox" id="product-hot" name="hot_product" value="1" {{ old('hot_product')
        ? 'checked' : '' }}>

                            <label for="product-hot" class="toggle-btn">🔥 HOT 🔥</label>
                        </div>
                        {{-- hết hothot --}}
                        {{-- trạng thái --}}
                        <div class="toggle-container">
                            <label class="label-text" for="status">Trạng thái</label>
                            <input type="hidden" name="status" value="0">
                            <label class="switch">
                                <input type="checkbox" id="statuss" name="status" value="1" {{ old(
        'status',
        $product->status ?? 1
    ) == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        {{-- --}}
                        {{-- hết trạng thái --}}

                    </div>
                    {{-- đối tượng --}}
                    <div>
                        <label class="label-text">Đối tượng</label>
                        <div class="customer_segments-checkbox">
                            @foreach ($customersegments as $segment)
                            <div class="customer-checkbox">
                                <input type="checkbox" id="customer-segment-{{ $segment->id }}"
                                    name="customer_segments[]" value="{{ $segment->id }}" {{ in_array(
                                        $segment->id,
                                        old('customer_segments', [])
                                    ) ? 'checked' : '' }}>
                                <label class="label-text"
                                    for="customer-segment-{{ $segment->id }}">{{ $segment->name
                                                                                                                                                                                                    }}</label>

                            </div>
                            @endforeach
                        </div>
                        @error('customer_segments')
                        <span class="baoloi">{{ $message }}</span>
                        @enderror

                    </div>
                    {{-- hết đối tượng --}}

                    {{-- đđ --}}
                </div>
            </div>

            <div>
                {{--ảnh concon --}}
                <div class="product-upload-status-hot">
                    <label class="label-text">Ảnh sản phẩm</label>
                    <div id="image-upload-container">
                        <div class="product-upload-img" data-index="0">
                            <div class="box-img-upload">
                                <img class="preview-image" id="preview-image-0" alt="Xem trước ảnh"
                                    style="display: none; width: 100%; height: auto;">
                                <i id="preview-icon-0" class="fa-solid fa-image"
                                    style="font-size: 48px; color: #aaa;"></i>
                                <label style="position: absolute; inset: 0; cursor: pointer;">
                                    <input class="input-text custom__input-file" type="file" name="img_products[]"
                                        accept="image/*" onchange="previewImagesForProductForm(event, 0)"
                                        style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                </label>
                                <!-- Dấu X để xóa hình ảnh, chỉ hiển thị khi đã có ảnh -->
                                <button type="button" class="remove-image" id="remove-image-0"
                                    onclick="removeImage(0)">❌</button>

                            </div>
                        </div>
                        <!-- Ô dấu cộng để thêm ảnh -->
                        <div class="product-upload-img-i add-more-image" onclick="addNewImageUpload()">
                            <div class="box-img-uploadd plus-iconn">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- hét ảnh con --}}
                <div>
                    {{-- thuong hiệu--}}
                    <div style="min-height: 110px">
                        <label class="label-text" for="product-brand">Thương hiệu</label>
                        <select id="product-brand" name="id_brand" class="@error('id_brand') is-invalid @enderror">
                            <option value="">Chọn thương hiệu</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('id_brand') == $brand->id ? 'selected' : '' }}>{{
                                    $brand->name }}</option>
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
                        <div class="product-variants">
                            <!-- Cột trái -->
                            <div class="product-variants-left">
                                <input class="input-text" type="text" placeholder="Tên Biến Thể"
                                    name="variants[option][]" value="{{ old('variants.option.0') }}">
                                <div class="product-variants-row">
                                    <input class="input-text" type="number" placeholder="Số Lượng" min="1"
                                        name="variants[stock][]" value="{{ old('variants.stock.0') }}"
                                        oninput="validatePrice(this)">
                                    <input class="input-text" type="number" min="1000" placeholder="Giá tiền"
                                        name="variants[price][]" value="{{ old('variants.price.0') }}"
                                        oninput="validatePrice(this)">
                                </div>
                            </div>
                            <!-- Cột phải -->
                            <div class="product-upload-status-variants" data-index="0">
                                <div class="product-upload-imggg">
                                    <div class="box-img-uploadd">
                                        <img class="preview-image" id="preview-image-variant-0"
                                            style="display: none; width: 100%; height: auto;" alt="Xem trước ảnh">
                                        <i class="fa-solid fa-image preview-icon"
                                            style="font-size: 48px; color: #aaa;"></i>
                                        <label style="position: absolute; inset: 0; cursor: pointer;">
                                            <input class="input-text custom__input-file" type="file"
                                                name="variants[image][]" onchange="previewImage(this, 0)"
                                                style="opacity: 0; width: 100%; height: 100%; cursor: pointer;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <!-- Nút xóa -->
                            <div class="button-product-variants">
                                <a href="#" type="button" class="delete-variant">Xóa</a>
                            </div>
                        </div>
                        @php
                        $variantErrors = collect($errors->getMessages())->filter(function ($value, $key) {
                        return Str::startsWith($key, 'variants');
                        });
                        @endphp

                        @if ($variantErrors->isNotEmpty())
                        <div class="baoloi">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                Vui lòng kiểm tra lại thông tin các biến thể (tên, giá, số lượng, hoặc hình ảnh).
                            </div>
                            @endif
                        </div>
                        @endif

                    </div>

                </div>



                <div class="button-product-variants">
                    <i class="fa-solid fa-circle-plus plus-icon-variants" onclick="Variants()"></i>
                </div>
                {{-- hết biên thểthể --}}
            </div>
        </div>
    </form>

</main>

<script src="{{ asset('/js/QuillEditText.js') }}" defer></script>
<script>
document.querySelector(".delete-link").addEventListener("click", function(event) {
    event.preventDefault(); // Ngăn chặn load lại trang
    // Thực hiện hành động xóa ở đây
    console.log("Đã click vào nút xóa!");
});
</script>
<script src="{{ asset('/js/Alerts.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const importDateInput = document.getElementById("product-import-date");

    if (importDateInput) {
        const today = new Date().toISOString().split("T")[0];

        importDateInput.setAttribute("max", today);

        if (!importDateInput.value) {
            importDateInput.value = today;
        }

        importDateInput.addEventListener("change", function() {
            if (this.value > today) {
                Swal.fire({
                    icon: "error",
                    title: "Ngày nhập không hợp lệ",
                    text: "Ngày nhập không được lớn hơn ngày hiện tại.",
                });

                this.value = today;
            }
        });
    }
});
</script>
@endsection