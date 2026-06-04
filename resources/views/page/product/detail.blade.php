@extends('layout')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<main class="deltai_layout">
    <!-- Nội dung chi tiết sản phẩm -->
    <script>
        let productVariants = @json($product->productVariants);
    </script>
    <div class="content-product">
        {{-- tets --}}
        <div class="image-container">
            <div class="left-images">
                @if($product->images->isNotEmpty())
                @foreach ($product->images as $image)
                <img class="thumbnail" src="{{ asset($image->img_products) }}"
                    data-big="{{ asset($image->img_products) }}" alt="Hình sản phẩm">
                @endforeach
                @else
                <p style="color: gray;">Chưa có hình ảnh sản phẩm.</p>
                @endif
            </div>

            <div class="right-image">
                <img id="main-image" src="{{ asset($product->productVariants->first()->image) }}" alt="Hình lớn">
            </div>
        </div>
        {{-- thông tin sp --}}
        <div class="info">
            <div class="product-card">
                <!-- Tên sản phẩm -->
                <h1 class="product-name">{{ $product->name }}</h1>

                <!-- ID sản phẩm & Giá tiền -->
                <div class="row">
                    <div class="info-box">
                        <div class="icon-box"><i class="fas fa-barcode"></i></div>
                        <div>
                            <span class="label">ID sản phẩm:</span>
                            <span class="value">{{ $product->id }}</span>
                        </div>
                    </div>
                    <div class="info-box">
                        <div class="icon-box"><i class="fas fa-dollar-sign"></i></div>
                        <div>
                            <span class="label">Giá tiền:</span>
                            <span id="product-price" class="value price">
                                {{ number_format($product->productVariants->first()->price, 0, ',', '.') }} đ
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="info-box">
                        <i class="fas fa-box"></i>
                        <span class="label">Số lượng:</span>
                        <span id="product-stock" class="value">{{ $product->productVariants->first()->stock }}</span>
                    </div>
                    <div class="info-box">
                        <i class="fas fa-check-circle"></i>
                        <span class="label">Trạng thái:</span>
                        <span class="value ">{{ $product->status ? 'Hiện' : 'Ẩn' }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="info-box">
                        <i class="fas fa-industry"></i>
                        <span class="label">Thương hiệu:</span>
                        <span class="value">{{ optional($product->brand)->name ?? 'Không xác định' }}</span>
                    </div>
                    <div class="info-box">
                        <i class="fas fa-layer-group"></i>
                        <span class="label">Danh mục:</span>
                        <span class="value">{{ optional($product->sub_category)->name ?? 'Không xác định' }}</span>
                    </div>
                </div>

                <!-- Nhóm Đối Tượng -->
                <div class="customer-segment">
                    <h2 class="segment-title">
                        <i class="fas fa-users"></i> Nhóm Đối Tượng
                    </h2>
                    <div class="segment-list">
                        @foreach ($product->customerSegments as $segment)
                        <div class="segment-box">
                            <div class="icon-box"><i class="fas fa-user-tag"></i></div>
                            <span class="segment-name">{{ $segment->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="product-variantss">
                    <h2 class="variant-title">
                        <i class="fas fa-tags"></i> Biến thể sản phẩm
                    </h2>
                    <div class="variant-list">
                        @foreach ($product->productVariants as $variant)
                        <button class="variant-btn" data-id="{{ $variant->id }}">
                            {{ $variant->option }}
                        </button>
                        @endforeach
                    </div>
                </div>


            </div>


            {{-- --}}
        </div>

        {{-- --}}
    </div>


    <!-- Hiển thị mô tả và thuộc tính -->

    <!-- show sanr pham -->
    <div class="contai_ner">
        <div>
            <div class="cott cot-detail-product">
                <div class="description description-detail-product">
                    <h3 style=" margin-bottom: 24px;text-align: center;">Mô tả sản phẩm</h3>
                    <p>{!! $product->description !!}</p>
                </div>
            </div>
        </div>

        <div>
            <div class="cott">
                <h3 style="text-align: center;">Thông tin sản phẩm TMDT</h3>

                @if($product->attributes && $product->attributes->count() > 0)
                <div class="product-attributes">
                    <div class="grid-header">Thuộc tính</div>
                    <div class="grid-header">Giá trị</div>

                    @foreach($product->attributes as $attribute)
                    <div class="grid-item">{{ $attribute->key }}</div>
                    <div class="grid-item">{{ $attribute->value }}</div>
                    @endforeach
                </div>
                @else
                <p style="text-align: center; color: gray;">Không có thông tin sản phẩm.</p>
                @endif
            </div>
        </div>
    </div>

</main>
@endsection