@extends('layout')

@section('title', 'QUẢN LÝ NGƯỜI DÙNG')
@section('content')
<main>
    <section class="page-main">
        <div class="header-filter-shared">
            <ul>
                <li>
                    <a href="{{ route('user')}}" class="{{ request()->query('query') == null ? 'active' : '' }}">
                        Tất cả
                    </a>
                </li>
                <li>
                    <a href="{{ route('user', ['query' => 'user_unverified']) }}"
                        class="{{ request()->query('query') == 'user_unverified' ? 'active' : '' }}">
                        Người dùng chưa xác thực
                    </a>
                </li>
                <li>
                    <a href="{{ route('user', ['query' => 'near_birthday']) }}"
                        class="{{ request()->query('query') == 'near_birthday' ? 'active' : '' }}">
                        Người dùng gần đến sinh nhật
                    </a>
                </li>
            </ul>
        </div>

        <div class="navbar">
            <form method="GET" class="glass-us">
                <div class="sr-container ">
                    <input type="text" name="key" placeholder="Tìm kiếm người dùng" value="{{ request()->key }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </form>

            <div class="filter-addbtn-right">
                <form method="GET" action="{{ route('user') }}" id="filter-form" class="xs-us">
                    <div class="sr-container">
                        <select name="filter" onchange="document.getElementById('filter-form').submit()">
                            <option value="newest" {{ request('filter')=='newest' ? 'selected' : '' }}>Người dùng mới
                            </option>
                            <option value="oldest" {{ request('filter')=='oldest' ? 'selected' : '' }}>Người dùng lâu
                                năm</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        @foreach($dhus as $us)
        @include('page.user.user_capquyen',['user'=>$us,'activeFormEditAdQuyen'=>old('user_id')])
        @endforeach
        <div class="grid_table_th_shared grid-userManager-layout">
            <div>ID</div>
            <div class="name-user">Tên</div>
            <div>Ngày sinh</div>
            <div>Giới tính</div>
            <div>Xác thực</div>
            <div>SĐT</div>
            <div>Địa chỉ</div>
            <div>Hành động</div>
        </div>
        @foreach($dhus as $us)
        <div
            class="grid_table_tb_shared grid-userManager-layout grid-userad subtable_tr {{ $us->account_lock == 0 ? 'locked' : '' }}">
            <div style="font-weight: 600">{{$us->id}}</div>
            <div class="name-user" style="justify-self: start">
                <div class="left">
                    <img src="{{ asset($us->avatar) }}" alt="avatar"
                        onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                </div>
                <div class="right">
                    <div class="right-tren">{{$us->last_name}} {{$us->first_name}} </div>
                    <div class="right-duoi">{{$us->email}}</div>
                </div>
            </div>
            <div class="ct">
                @if($us->dob )
                {{$us->dob }}
                @else
                <p style="opacity: 0.4">Chưa có</p>
                @endif
            </div>
            <div class="ct">@if ($us->gender == 1)
                Nam
                @elseif ($us->gender == 2)
                Nữ
                @else
                <p style="opacity: 0.4">Chưa có</p>
                @endif
            </div>
            <div class="ct" style="text-align: center">
                @if($us->email_verified_at)
                {{ $us->email_verified_at->format('d/m/Y') }} <br>
                {{ $us->email_verified_at->format('H:i:s') }}
                @else
                <p style="opacity: 0.4">Chưa xác thực</p>
                @endif
            </div>
            <div class="ct">{{$us->phone}}</div>
            <div class="usad ngat_hang">
                @if($us->address)
                {{$us->address}}
                @else
                <p style="opacity: 0.4 ;">Chưa có</p>
                @endif
            </div>
            <form action="{{route('user.block')}}" id="changeUserLock" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id" id="idUserInput">
            </form>
            <div class="actions-d grid-css ct">
                <a href="{{route('dtus', ['id' => $us->id,'orderquery' => 'all'])}}" title="Xem chi tiết" class="border-green hover_scale_btn">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <button class="oppenCreateAdmin border-yellow hover_scale_btn" data-userAD-id="{{$us->id}}" title="Cấp quyền">
                    <i class="fa-solid fa-user-plus"></i>
                </button>
                <button class="acc-lock-user border-red hover_scale_btn" title="{{$us->account_lock ? 'Nhấn để khóa' : 'Nhấn để mở'}}" data-id-user="{{$us->id}}">
                    <i class="fa-solid {{$us->account_lock  ? 'fa-unlock' : 'fa-lock'}}"></i>
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
                    <input type="hidden" name="thutu" value="{{ request('thutu') }}">
                </form>

            </div>
            <div class="right">
                @if ($dhus->lastPage() > 1)
                <ul>
                    <!-- nút left -->
                    <li class="{{$pageht}} == 1 ? 'disable' : ''">
                        <a href="{{$dhus->appends(request()->query())->previousPageUrl()}}"><i
                                class="fa-solid fa-caret-left"></i></a>
                    </li>
                    <!-- các trang -->
                    @if ($start > 1)
                    <li><a href="{{$dhus->appends(request()->query())->url(1)}}">1</a>
                    </li>
                    @if($start > 2)
                    <li class="disabled"><span>...</span></li>
                    @endif
                    @endif
                    @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}"><a
                            href="{{$dhus->appends(request()->query())->url($i) }}">{{$i}}</a>
                        </li>
                        @endfor
                        @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span></li>
                            @endif
                            <li><a href="{{$dhus->appends(request()->query())->url($lapa)}}">{{$lapa}}</a></li>
                            @endif
                            <li class="{{($pageht == $lapa) ? 'disabled' : ''}}">
                                @if($pageht == $lapa)
                                <a href="{{$dhus->appends(request()->query())->url(1)}}"><i
                                        class="fa-solid fa-caret-right"></i></a>
                                @else
                                <a href="{{$dhus->appends(request()->query())->nextPageUrl()}}"><i
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