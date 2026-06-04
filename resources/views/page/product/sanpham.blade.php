@extends('layout')

@section('title', 'Quản lý sản phẩm')

@section('content')
<main>
  <section class="page-main">
    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('page.product.sanpham')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>
        <li>
          <a href="{{ route('page.product.sanpham', ['query' => 'hot']) }}"
            class="{{ request()->query('query') == 'hot' ? 'active' : '' }}">
            Sản phẩm Hot
          </a>
        </li>
        <li>
          <a href="{{ route('page.product.sanpham', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Sản phẩm Ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('page.product.sanpham', ['query' => 'sale']) }}"
            class="{{ request()->query('query') == 'sale' ? 'active' : '' }}">
            Sản phẩm Sale
          </a>
        </li>
        <li>
          <a href="{{ route('page.product.sanpham', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Sản phẩm xóa
          </a>
        </li>
      </ul>
    </div>

    <div class="navbar">
      <form method="GET" class="glass-us">
        <div class="sr-container">
          <input type="text" name="key" placeholder="Tìm kiếm sản phẩm" value="{{ request()->key }}">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </form>


      <div class="filter-addbtn-right">
        <form method="GET" action="{{ route('page.product.sanpham') }}" id="filter-form" class="xs-us">
          <div class="sr-container">
            <select name="filter" onchange="document.getElementById('filter-form').submit()">
              <option value="">-- Chọn --</option>
              <option value="price_asc" {{ request('filter')=='price_asc' ? 'selected' : '' }}>Giá thấp đến cao</option>
              <option value="price_desc" {{ request('filter')=='price_desc' ? 'selected' : '' }}>Giá cao đến thấp
              </option>
              <option value="date_newest" {{ request('filter')=='date_newest' ? 'selected' : '' }}>Ngày mới nhất
              </option>
              <option value="date_oldest" {{ request('filter')=='date_oldest' ? 'selected' : '' }}>Ngày cũ nhất</option>
            </select>
          </div>
        </form>

        <div class="cr-sp ">
          <a href="{{ route('page.product.add') }}" >
            <i class="fa-solid fa-circle-plus"></i> Thêm sản phẩm
          </a>
        </div>
      </div>
    </div>


    <div class=" grid_table_th_shared grid-products-layout ">
      <div>ID</div>
      <div>Tên sản phẩm</div>
      <div>Hot</div>
      <div>Ảnh</div>
      <div>Giá</div>
      <div>Giảm giá</div>
      <div class="custom-select">
        <form method="GET">
          <select class="" name="category_id" onchange="this.form.submit()">
            <option value="">Danh mục</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request()->category_id == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
            @endforeach
          </select>
        </form>
      </div>
      <div>Tồn kho</div>
      <div class="custom-select">
        <form method="GET">
          <select name="brand_id" onchange="this.form.submit()">
            <option value="">Thương hiệu</option>
            @foreach($brands as $th)
            <option value="{{ $th->id }}" {{ request()->brand_id == $th->id ? 'selected' : '' }}>
              {{ $th->name }}
            </option>
            @endforeach
          </select>
        </form>
      </div>
      <div>Trạng thái</div>
      <div>Hành động</div>
    </div>

    @foreach($products as $sp)
    <div class="grid_table_tb_shared grid-products-layout">
      <div class="gird-css ct" style="font-weight: 600">{{$sp->id}}</div>
      <div class="gird-css ct">{{$sp->name}}</div>
      <div class="gird-css ct">
        <form class="toggle-hot-form" action="{{ route('product.toggleHot', $sp->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" style="background:none; border:none; cursor:pointer;">
              @if($sp->hot_product == 1)
                  <i class="fa-solid fa-star hover_scale_btn" style="color: rgb(217, 221, 0)"></i>
              @else
                  <i class="fa-regular fa-star hover_scale_btn" style="color: #aaa;"></i>
              @endif
          </button>
      </form>

      </div>
      <div class="gird-css ct">
        @if ($sp->image)
        <img src="{{ asset( $sp->image) }}" alt="Loading..." width="70px" />
        @else
        Không có ảnh
        @endif
      </div>
      <div class="gird-css ct">
        <div>
          @if ($sp->same_price)
          {{ number_format($sp->min_price, 0, '.', '.') }}đ
          @else
          <div style="display: flex; flex-direction: column; align-items: center;">
            <span>{{ number_format($sp->min_price, 0, '.', '.') }}đ</span>
            <span style="display: block; width: 50%; border-top: 2px solid black; margin: 5px 0;"></span>
            <span>{{ number_format($sp->max_price, 0, '.', '.') }}đ</span>
          </div>
          @endif
        </div>
      </div>
      <div class="gird-css ct">
        <div class="discount-info">
          @if ($sp->discount_status === 'active')
          {{-- <div>Mã:{{$sp->id_discount}}</div> --}}
          <div class="gg">{{$sp->discount_value}}%</div>
          <div class="gg">Đang giảm</div>
          @elseif ($sp->discount_status === 'upcoming')
          {{-- <div>{{$sp->id_discount}}</div> --}}
          <div class="sg">{{$sp->discount_value}}%</div>
          <div class="sg">Sắp giảm</div>

          @elseif ($sp->discount_status === 'expired')
          <div class="expired-text">Hết hạn</div>
          @else
          <div class="no-discount-text">Không giảm</div>
          @endif
        </div>
      </div>
      <div class="gird-css ct">
          @if ($sp->sub_category)
          {{ $sp->sub_category->name }}
          @else
              Danh mục đã bị ẩn
          @endif
      </div>
      <div class="gird-css ct">{{$sp->total_stock}}</div>
      <div class="gird-css ct">
        @if($sp->brand)
        {{$sp->brand->name}}
        @else
        Thương hiệu ẩn
        @endif
      </div>
      {{-- <div class="gird-css ct"> {{ optional($sp->brand)->name ?? 'Không có thương hiệu' }}</div> --}}
      <div class="gird-css ct">
        <form action="{{ route('product.toggleStatus', $sp->id) }}" method="POST" style="display:inline;" class="form-toggle-status">
          @csrf
          <button type="submit" class="{{ $sp->status == 1 ? 'icon-status-btns icon-status-mount' : 'icon-status-btns icon-status-unmount' }}" title="{{ $sp->status == 1 ? 'Nhấn để ẩn' : 'Nhấn để hiện' }}" style="background:none; border:none; cursor:pointer;">
              @if($sp->status == 1)
                  <i class="fa-solid fa-eye hover_scale_btn"></i>
              @else
                  <i class="fa-solid fa-eye-slash hover_scale_btn"></i>
              @endif
          </button>
      </form>
      
      </div>
      <div class="actions-d  ct">
        @if(request()->query('query') === 'deleted')
        <form action="{{ route('products.restore', $sp->id) }}" method="POST" style="display:inline;" class="form-restore">
          @csrf
          <button type="button" class="btn-restore border-green hover_scale_btn" title="Khôi phục">
            <i class="fa-solid fa-rotate-left"></i>
            {{-- <p>Khôi phục</p> --}}
          </button>
        </form>

        @else
        <a href="{{ route('page.product.detail', ['id' => $sp->id]) }}" title="Xem chi tiết"
          class="border-green hover_scale_btn hover_scale_btn">
          <i class="fa-solid fa-eye"></i>
        </a>
        <a href="{{ route('page.product.edit', ['id' => $sp->id]) }}" title="Sửa sản phẩm" class="border-yellow hover_scale_btn">
          <i class="fa-regular fa-pen-to-square"></i>
        </a>
        <form action="{{ route('products.destroy', $sp->id) }}" method="POST" class="delete-form">
          @csrf
          <button type="submit" title="Xóa sản phẩm" class="border-red hover_scale_btn">
            <i class="fa-regular fa-trash-can"></i>
          </button>
        </form>
        @endif
      </div>
    </div>
    </div>
    @endforeach
    @if (!empty($notFoundMessage))
    <div
      style="padding: 10px; margin-top:10px; background-color: #ffecec; color: #d8000c; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 15px;display:inline-block">
      {{ $notFoundMessage }}
    </div>
    @endif

    <div class="end-user">
      <div class="left">
        <form method="GET" id="show1" class="show-sl">
          <label for="">Hiển Thị:</label>
          <select name="show" id="show">
            <option value="10" {{request('show')==10 ? 'selected' : '' }}>10</option>
            <option value="2" {{request('show')==2 ? 'selected' : '' }}>2</option>
            <option value="30" {{request('show')==30 ? 'selected' : '' }}>30</option>
            <option value="50" {{request('show')==50 ? 'selected' : '' }}>50</option>
            <option value="100" {{request('show')==100 ? 'selected' : '' }}>100</option>
            <option value="150" {{request('show')==150 ? 'selected' : '' }}>150</option>
          </select>
          <!-- giữ lại tham số -->
          <input type="hidden" name="key" value="{{ request('key') }}">
          <input type="hidden" name="thutu" value="{{ request('thutu') }}">
        </form>

      </div>
      <div class="right">
        @if ($products->lastPage() > 1)
        <ul>
          <!-- nút left -->
          <li class="{{$pageht}} == 1 ? 'disable' : ''">
            <a href="{{$products->appends(request()->query())->previousPageUrl()}}"><i
                class="fa-solid fa-caret-left"></i></a>
          </li>
          <!-- các trang -->
          @if ($start > 1)
          <li><a href="{{$products->appends(request()->query())->url(1)}}">1</a>
          </li>
          @if($start > 2)
          <li class="disabled"><span>...</span></li>
          @endif
          @endif
          @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
              href="{{$products->appends(request()->query())->url($i) }}">{{$i}}</a></li>
            @endfor


            @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
              @endif
              <li><a href="{{$products->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
              @endif

              <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                @if($pageht == $lapa)
                <a href="{{$products->appends(request()->query())->url(1)}}"><i class="fa-solid fa-caret-right"></i></a>
                @else
                <a href="{{$products->appends(request()->query())->nextPageUrl()}}"><i
                    class="fa-solid fa-caret-right"></i></a>
                @endif
              </li>
        </ul>
        @endif
      </div>
    </div>
  </section>

</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection