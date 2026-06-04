@extends('layout')

@section('title','QUẢN LÝ BANNER')

@section('content')
<main>
    <section class="page-main">
        <div class="header-filter-shared">
            <ul>
                <li>
                    <a href="{{ route('banner.show',array_merge(request()->query(), ['banner' => 'all'])) }}"
                        class="{{ request()->routeIs('banner.show') && request()->query('banner') == 'all' ? 'active' : ''  }}">
                        Tất
                        cả
                    </a>
                </li>
                <li>
                    <a href="{{ route('banner.show',array_merge(request()->query(), ['banner' => '1'])) }}"
                        class="{{ request()->routeIs('banner.show') && request()->query('banner') == '1' ? 'active' : ''  }}">
                        Banner
                        Lớn
                    </a>
                </li>
                <li>
                    <a href="{{ route('banner.show',array_merge(request()->query(), ['banner' => '2'])) }}"
                        class="{{ request()->routeIs('banner.show') && request()->query('banner') == '2' ? 'active' : ''  }}">
                        Banner
                        trong SP
                    </a>
                </li>
                <li>
                    <a href="{{ route('banner.show',array_merge(request()->query(), ['banner' => '0'])) }}"
                        class="{{ request()->routeIs('banner.show') && request()->query('banner') == '0' ? 'active' : ''  }}">
                        Banner
                        Sale
                    </a>
                </li>
                <li>
                    <a href="{{ route('banner.show',array_merge(request()->query(), ['banner' => '3'])) }}"
                        class="{{ request()->routeIs('banner.show') && request()->query('banner') == '3' ? 'acti' : ''  }} rac">
                        Thùng
                        Rác
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar">
            <form method="GET" class="glass-us">
                <div class="sr-container">
                    <input type="text" name="key" placeholder="Tìm kiếm..." value="{{ request()->key }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="hidden" name="show" value="{{ request('show') }}">
                <input type="hidden" name="page" value="{{ request('page') }}">
                <input type="hidden" name="thutu" value="{{ request('thutu') }}">
                <input type="hidden" name="banner" value="{{ request('banner') }}">
            </form>
            <div class="filter-addbtn-right">
                <form method="GET" id="thutu1" class="xs-us">
                    <div class="sr-container"> <select name="thutu" id="thutu">
                            <option value="" {{ request('thutu')=='' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="1" {{ request('thutu')=='1' ? 'selected' : '' }}>Mới cập nhật</option>
                            <option value="2" {{ request('thutu')=='2' ? 'selected' : '' }}>Thứ tự</option>
                        </select>
                        <input type="hidden" name="key" value="{{ request('key') }}">
                        <input type="hidden" name="show" value="{{ request('show') }}">
                        <input type="hidden" name="banner" value="{{ request('banner') }}">
                        <input type="hidden" name="page" value="{{ request('page') }}">
                    </div>
                </form>
                <div class="cr-sp">
                    <button id="openModalBnCr"> <i class="fa-solid fa-circle-plus"></i>Thêm Banner</button>
                </div>
            </div>
        </div>
        @include('page.banner.create_banner')
        @foreach($banner as $bn)
        @include('page.banner.edit_banner', ['banner' => $bn])
        @endforeach
        <div class="grid_table_th_shared grid-banner-layout">
            <div>ID</div>
            <div>Vị Trí</div>
            <div>Ảnh</div>
            <div>Link</div>
            <div>Tiêu đề 1</div>
            <div>Tiêu đề 2</div>
            <div>Thứ tự</div>
            <div>Hành động</div>
        </div>
        @foreach($banner as $bn)
        <div class=" grid_table_tb_shared grid-banner-layout subtable_tr">
            <div>{{$bn->id}}</div>
            <div>{{$bn->position == 1 ? 'Banner Lớn' : ($bn->position == 2 ? 'Banner trong sản
                phẩm' : ($bn->position == 3 ? 'Banner Sale' : 'Lỗi'))}}</div>
            <div style="padding: 10px">
                <img src="{{asset($bn->image)}}" style="width: 100%" alt="">
            </div>
            <div style="justify-self: start">{{$bn->link}}</div>
            <div>{{$bn->title1}}</div>
            <div style="justify-self: start">{{$bn->title2}}</div>
            <div>{{$bn->sort}}</div>
            @if(is_null($bn->deleted_at))
            <div class="actions-d grid-css ct">
                <button class="openModalBnEdit hover_scale_btn border-yellow" data-banner-id="{{ $bn->id }}" title="Sửa banner">
                    <i class="fa-regular fa-pen-to-square"></i>
                </button>
                <button class="border-red delete-bn hover_scale_btn" title="Xóa banner" data-id-bn="{{$bn->id}}">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
                <form id="form-delete-{{$bn->id}}" action="{{route('banner.delete',$bn->id)}}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
            @else
            <div class="actions-d grid-css ct">
                <button class="border-green delete-bn-kp hover_scale_btn" title="Khôi phục" data-id-bnres="{{$bn->id}}">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
                <form id="form-delete-kp-{{$bn->id}}" action="{{route('banner.restore',$bn->id)}}" method="POST"
                    style="display: none;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" style="display: none;"></button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
        <div class="end-user">
            <div class="left">
                <form method="GET" id="show1" class="show-sl">
                    <label for="">Hiển Thị:</label>
                    <select name="show" id="show">
                        <option value="5" {{request('show')==5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{request('show')==10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{request('show')==50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{request('show')==100 ? 'selected' : '' }}>100</option>
                        <option value="150" {{request('show')==150 ? 'selected' : '' }}>150</option>
                    </select>
                    <!-- giữ lại tham số -->
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    <input type="hidden" name="banner" value="{{ request('banner') }}">
                    <input type="hidden" name="thutu" value="{{ request('thutu') }}">
                </form>
            </div>
            <div class="right">
                @if ($banner->lastPage() > 1)
                <ul>
                    <!-- nút left -->
                    <li class="{{$pageht}} == 1 ? 'disable' : ''">
                        <a href="{{$banner->appends(request()->query())->previousPageUrl()}}"><i
                                class="fa-solid fa-caret-left"></i></a>
                    </li>
                    <!-- các trang -->
                    @if ($start > 1)
                    <li><a href="{{$banner->appends(request()->query())->url(1)}}">1</a></li>
                    @if($start > 2)
                    <li class="disabled"><span>...</span></li>
                    @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
                            href="{{$banner->appends(request()->query())->url($i) }}">{{$i}}</a></li>
                        @endfor
                        @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
                            @endif
                            <li><a href="{{$banner->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
                            @endif
                            <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                                @if($pageht == $lapa)
                                <a href="{{$banner->appends(request()->query())->url(1)}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @else
                                <a href="{{$banner->appends(request()->query())->nextPageUrl()}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @endif
                            </li>
                </ul>
                @endif
            </div>
        </div>
    </section>
</main>
@if ($errors->any())
<script>
    localStorage.setItem('modalOppen', 'true');
</script>
@endif
@endsection