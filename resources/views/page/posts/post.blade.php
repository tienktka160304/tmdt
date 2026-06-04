@extends('layout')

@section('title','Quản lý bài viết')

@section('content')
<main>
  <section class="page-main">

    <div class="header-filter-shared">
      <ul>
        <li>
          <a href="{{ route('baiviet')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
            Tất cả
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'hot']) }}"
            class="{{ request()->query('query') == 'hot' ? 'active' : '' }}">
            Bài viết nổi bật
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'hidden']) }}"
            class="{{ request()->query('query') == 'hidden' ? 'active' : '' }}">
            Bài viết ẩn
          </a>
        </li>
        <li>
          <a href="{{ route('baiviet', ['query' => 'deleted']) }}"
            class="{{ request()->query('query') == 'deleted' ? 'active' : '' }}">
            Bài viết xóa
          </a>
        </li>
      </ul>
    </div>

    <div class="navbar">

      <form method="GET" class="glass-us">
        <div class="sr-container">
          <input type="text" name="key" placeholder="Tìm kiếm bài viết" value="{{ request()->key }}">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </form>

      <div class="filter-addbtn-right">
        <form method="GET" id="thutu1" class="xs-us">
          <div class="sr-container">
            <select name="sort" onchange="this.form.submit()">
              <option value="">Lọc bài viết</option>
              <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
              <option value="oldest" {{ request()->sort == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
              <option value="view_asc" {{ request()->sort == 'view_asc' ? 'selected' : '' }}>Lượt xem từ thấp đến cao
              </option>
              <option value="view_desc" {{ request()->sort == 'view_desc' ? 'selected' : '' }}>Lượt xem từ cao đến thấp
              </option>
            </select>
          </div>
        </form>
        <div class="cr-sp">
          {{-- <h1 class="tsp">Quản lý bài viết</h1> --}}
          <a href="{{ route('page.post.create_post') }}">
            <i class="fa-solid fa-circle-plus"></i> Thêm bài viết
          </a>
        </div>
      </div>
    </div>



    <div class=" grid_table_th_shared grid-post-layout">
      <div>ID</div>
      <div>Tiêu đề</div>
      <div>Mô tả</div>
      <div>Nổi bật</div>
      <div>Lượt xem</div>
      <div>Trạng thái</div>
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
      <div>Ngày đăng</div>
      <div>Ảnh</div>
      <div>Hành động</div>
    </div>

    @foreach($post as $bv)
    <div class="grid_table_tb_shared grid-post-layout">
      <div class="gird-css ct" style="font-weight: 600">{{$bv->id}}</div>
      <div class="gird-css ct">
        <p class="">
          {{$bv->title}}
        </p>
      </div>
      <div class="gird-css ct">
        <p title="{{ e($bv->short_description) }}" style="
            color:#7e7e7e;
            padding: 0 5px;
            overflow: hidden; display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;">
          {{$bv->short_description}}
        </p>
      </div>
      <div class="gird-css ct">
        <form class="toggle-hot-form" action="{{ route('post.toggleHot', $bv->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" title="{{$bv->hot ? 'Nhấn để tắt Hot' : 'Nhấn để Hot'}}" style="background:none; border:none; cursor:pointer;">
            @if($bv->hot == 1)
            <i class="fa-solid fa-star hover_scale_btn" style="color: rgb(217, 221, 0)"></i>
            @else
            <i class="fa-regular fa-star hover_scale_btn" style="color: #aaa;"></i>
            @endif
          </button>
        </form>
      </div>
      <div class="gird-css ct">{{$bv->views}}</div>
      <div class="grid-css ct">
        <form action="{{ route('post.toggleStatus', $bv->id) }}" method="POST" style="display:inline;" class="form-toggle-status">
          @csrf
          <button type="submit" style="background:none; border:none; cursor:pointer;">
            @if($bv->status == 1)
            <button class="icon-status-btns icon-status-mount hover_scale_btn" title="Nhấn để ẩn">
              <i class="fa-solid fa-eye"></i>
            </button>
            @else
            <button class="icon-status-btns icon-status-unmount hover_scale_btn" title="Nhấn để hiện">
              <i class="fa-solid fa-eye-slash"></i>
            </button>
            @endif
          </button>
      </form>
<script>
   document.addEventListener('DOMContentLoaded', function() {
    const formToggleStatusList = document.querySelectorAll('.form-toggle-status');
    formToggleStatusList.forEach(formToggleStatus => {
        formToggleStatus.addEventListener('submit', function(event) {
            event.preventDefault(); // Ngăn submit tự động

            confirmAction({
                title: 'Bạn có chắc muốn thay đổi trạng thái?',
                text: 'Thao tác này sẽ thay đổi trạng thái hiển thị bài viết.',
                confirmText: 'Đồng ý',
                cancelText: 'Hủy',
                confirmColor: "#008000",
                cancelColor: "#3085d6",
                icon: 'warning'
            }).then((result) => {
                if (result.isConfirmed) {
                    formToggleStatus.submit(); // Nếu xác nhận thì submit
                }
            });
        });
    });
});
</script>
    </div>
      <div class="gird-css ct">{{$bv->post_category->name}}</div>
      <div class="gird-css ct">{{ \Carbon\Carbon::parse($bv->published_date)->format('d/m/Y') }}</div>
      <div class="gird-css ct"><img src="{{ asset( $bv->image) }}" width="90px" alt="Loading..." />
      </div>
      <div class="actions-d  ct">
        @if(request()->query('query') === 'deleted')
        <form action="{{ route('baiviet.restore', $bv->id) }}" method="POST" style="display:inline;" id="form-restore-{{ $bv->id }}">
          @csrf
          <button type="submit" title="Khôi phục" class="border-green restore-post-btn hover_scale_btn" data-restore-id="{{ $bv->id }}">
            <i class="fa-solid fa-rotate-left"></i>
          </button>
          {{-- <p>Khôi phục</p> --}}
        </form>        
        @else
        <a href="{{ route('post.show', $bv->id) }}" title="Xem chi tiết" class="border-green hover_scale_btn">
          <i class=" fa-solid fa-eye"></i>
        </a>
        <a href="{{ route('page.post.edit', ['id' => $bv->id]) }}" title="Sửa bài viết" class="border-yellow hover_scale_btn">
          <i class="fa-regular fa-pen-to-square"></i>
        </a>
        <form id="form-delete-{{ $bv->id }}" action="{{ route('post.destroy', $bv->id) }}" method="POST">
          @csrf
          <button type="button" class=" border-red hover_scale_btn" title="Xóa bài viết" onclick="confirmDelete_post({{ $bv->id }})">
            <i class="fa-regular fa-trash-can"></i>
          </button>
        </form>
        @endif
      </div>

    </div>

    @endforeach
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
          <input type="hidden" name="postQuery" value="{{ request('postQuery') }}">
        </form>

      </div>
      <div class="right">
        @if ($post->lastPage() > 1)
        <ul>
          <!-- nút left -->
          <li class="{{$pageht}} == 1 ? 'disable' : ''">
            <a href="{{$post->appends(request()->query())->previousPageUrl()}}"><i
                class="fa-solid fa-caret-left"></i></a>
          </li>
          <!-- các trang -->
          @if ($start > 1)
          <li><a href="{{$post->appends(request()->query())->url(1)}}">1</a>
          </li>
          @if($start > 2)
          <li class="disabled"><span>...</span></li>
          @endif
          @endif
          @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
              href="{{$post->appends(request()->query())->url($i) }}">{{$i}}</a></li>
            @endfor


            @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
              @endif
              <li><a href="{{$post->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
              @endif

              <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                @if($pageht == $lapa)
                <a href="{{$post->appends(request()->query())->url(1)}}"><i class="fa-solid fa-caret-right"></i></a>
                @else
                <a href="{{$post->appends(request()->query())->nextPageUrl()}}"><i
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