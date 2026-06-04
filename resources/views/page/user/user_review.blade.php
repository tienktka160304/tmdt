@extends('layout')

@section('title', 'QUẢN LÝ ĐÁNH GIÁ')
@section('content')
<main>
    <section class="page-main">
        <div class="h1-key">
            <form method="GET" class="glass-us">
                <div class="sr-container">
                    <input type="text" name="key" placeholder="Tìm kiếm..." value="{{ request()->key }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </form>
        </div>

        <div class="grid-reviews-layout grid_table_th_shared ">
            <div>ID</div>
            <div class="name-user">Tên</div>
            <div>Nội dung</div>
            <div>Thời gian</div>
            <div>Trạng thái</div>
        </div>
        @foreach($userRe as $us)
        <div class="grid-reviews-layout grid_table_tb_shared {{$us->status == 1 ? '' : 'locked'}}">
            <div class="ct" style="font-weight: 600">{{$us->id}}</div>
            <div class="name-user" style=" justify-self: start; padding-right: 5px;">
                <div class="left">
                    <img src="{{ asset($us->user->avatar) }}" alt="avatar"
                        onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                </div>
                <div class="right">
                    <div class="right-tren">{{$us->user->last_name}} {{$us->user->first_name}}
                    </div>
                    <div class="right-duoi">{{$us->user->email}}</div>
                </div>
            </div>
            <div class="usad ngat_hang" style=" justify-self: start">{{$us->content}}</div>
            <div class="ct">{{$us->created_at->isoFormat('D MMMM YYYY')}}</div>
            <form action="{{route('change.status')}}" id="changeUserReview" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id" id="idInput">
            </form>
            <div class="actions-d grid-css ct">

                @if($us->status == 1)
                <button class="icon-status-btns icon-status-mount change-status hover_scale_btn" data-id="{{$us->id}}"
                    title="Nhấn để ẩn">
                    <i class=" fa-solid fa-eye"></i>
                </button>
                @else
                <button class="icon-status-btns icon-status-unmount change-status hover_scale_btn" data-id="{{$us->id}}"
                    title="Nhấn để hiện">
                    <i class=" fa-solid fa-eye-slash"></i>
                </button>
                @endif
                </button>

            </div>
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
                    <!-- <input type="hidden" name="thutu" value="{{ request('thutu') }}"> -->
                </form>

            </div>
            <div class="right">
                @if ($userRe->lastPage() > 1)
                <ul>
                    <!-- nút left -->
                    <li class="{{$pageht}} == 1 ? 'disable' : ''">
                        <a href="{{$userRe->appends(request()->query())->previousPageUrl()}}"><i
                                class="fa-solid fa-caret-left"></i></a>
                    </li>
                    <!-- các trang -->
                    @if ($start > 1)
                    <li><a href="{{$userRe->appends(request()->query())->url(1)}}">1</a>
                    </li>
                    @if($start > 2)
                    <li class="disabled"><span>...</span></li>
                    @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
                            href="{{$userRe->appends(request()->query())->url($i) }}">{{$i}}</a></li>
                        @endfor


                        @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
                            @endif
                            <li><a href="{{$userRe->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
                            @endif

                            <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                                @if($pageht == $lapa)
                                <a href="{{$userRe->appends(request()->query())->url(1)}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @else
                                <a href="{{$userRe->appends(request()->query())->nextPageUrl()}}"><i
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