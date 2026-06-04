@extends('layout')

@section('title', 'Quản lý bình luận')

@section('content')

<main>

  <section class="page-main">

    <div class="navbar">
      <form method="GET" class="glass-us">
        <div class="sr-container">
          <input type="text" name="keyword" placeholder="Tìm kiếm bình luận" value="{{ request()->keyword }}">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
      </form>
      <div class="filter-addbtn-right">
        <form method="GET" id="thutu1" class="xs-us">
          <div class="sr-container">
            <select name="sort" onchange="this.form.submit()">
              <option value="">Mặc định</option>
              {{-- <option value="newest" {{ request()->sort == 'newest' ? 'selected' : '' }}>Cũ nhất</option> --}}
              <option value="oldest" {{ request()->sort == 'oldest' ? 'selected' : '' }}>Mới nhất</option>
            </select>
          </div>
        </form>
      </div>
    </div>


    <div class="grid-comment-layout grid_table_th_shared ">
      <div>ID</div>
      <div>Tên</div>
      <div class="custom-select">
        <form method="GET">
          <select class="" name="id_product" onchange="this.form.submit()">
            <option value="">Mã SP</option>
            @foreach($products as $sp)
            <option value="{{ $sp->id }}" {{ request()->sp_id == $sp->id ? 'selected' : '' }}>
              {{ $sp->id }}
            </option>
            @endforeach
          </select>
        </form>
      </div>
      <div>Sản phẩm</div>
      <div>Nội dung</div>
      <div>Ngày</div>
      <div>Hành động</div>
    </div>

    @foreach($comment as $bl)
    <div class="grid-comment-layout grid_table_tb_shared  {{ $bl->status == 0 ? 'locked' : '' }}">
      <div class="gird-css ct" style="font-weight: 600">{{$bl->id}}</div>
      <div class="gird-css ct">{{$bl->user->last_name}} {{$bl->user->first_name }} </div>
      <div class="gird-css ct">{{$bl->products->id}}</div>
      <div class="gird-css ct"> {{$bl->products->name}}</div>
      <div class="gird-css ct" style="justify-self: left; padding: 5px;">{{$bl->content}}</div>
      <div class="gird-css ct">{{ \Carbon\Carbon::parse($bl->created_at)->format('d/m/Y') }}</div>
      <div class="actions-d  ct">
        <form action="{{ route('update.status', $bl->id) }}" method="POST" style="display:inline;">
          @csrf
          @method('PUT')
          <button type="button" class="border-yellow alerts-lock-open hover_scale_btn"
            title="{{$bl->status ? 'Nhấn để khóa' : 'Nhấn để mở'}}">
            <i class="lock-icon fa-solid {{ $bl->status ? 'fa-unlock' : 'fa-lock' }}"></i>
            {{-- <p>{{ $bl->status ? 'Khóa' : 'Mở' }}</p> --}}
          </button>
        </form>
        <form action="{{ route('comment.destroy', $bl->id) }}" method="POST" style="display:inline;"
          class="form-delete-comment">
          @csrf
          @method('DELETE')
          <button type="button" title="Xóa bình luận" class="border-red btn-delete-comment hover_scale_btn">
            <i class="fa-regular fa-trash-can"></i>
            {{-- <p>Xóa</p> --}}
          </button>
        </form>
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
        </form>

      </div>
      <div class="right">
        @if ($comment->lastPage() > 1)
        <ul>
          <!-- nút left -->
          <li class="{{$pageht}} == 1 ? 'disable' : ''">
            <a href="{{$comment->appends(request()->query())->previousPageUrl()}}"><i
                class="fa-solid fa-caret-left"></i></a>
          </li>
          <!-- các trang -->
          @if ($start > 1)
          <li><a href="{{$comment->appends(request()->query())->url(1)}}">1</a>
          </li>
          @if($start > 2)
          <li class="disabled"><span>...</span></li>
          @endif
          @endif
          @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
              href="{{$comment->appends(request()->query())->url($i) }}">{{$i}}</a></li>
            @endfor


            @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
              @endif
              <li><a href="{{$comment->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
              @endif

              <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                @if($pageht == $lapa)
                <a href="{{$comment->appends(request()->query())->url(1)}}"><i class="fa-solid fa-caret-right"></i></a>
                @else
                <a href="{{$comment->appends(request()->query())->nextPageUrl()}}"><i
                    class="fa-solid fa-caret-right"></i></a>
                @endif
              </li>
        </ul>
        @endif
      </div>
    </div>
  </section>


</main>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection