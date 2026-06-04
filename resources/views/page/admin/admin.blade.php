@extends('layout')

@section('title', 'QUẢN LÝ NHÂN VIÊN')
@section('content')
<main>
    <section class="page-main">
        <div class="h1-key navbar">
            <form method="GET" class="glass-us">
                <div class="sr-container">
                    <input type="text" name="key" placeholder="Tìm kiếm" value="{{ request()->key }}">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
            </form>
            <div class="filter-addbtn-right">
                <form method="GET" id="thutu1" class="xs-us">
                    <div class="sr-container"> <select name="thutu" id="thutu">
                            <option value="">Z-A</option>
                            <option value="1" {{ $thutu=='1' ? 'selected' : '' }}>A-Z</option>
                            <option value="2" {{ $thutu=='2' ? 'selected' : '' }}>Cấp độ</option>
                        </select></div>
                </form>
            </div>
        </div>
        @foreach($ad as $us)
        @include('page.admin.edit',['user'=>$us,'activeFormEditAd'=>old('user_id')])
        @endforeach
        <div class="grid-admin-layout grid_table_th_shared">
            <div>Tên</div>
            <div>Ngày sinh</div>
            <div>Giới tính</div>
            <div>Xác thực</div>
            <div>Cấp</div>
            <div>SĐT</div>
            <div>Địa chỉ</div>
            <div>Hành động</div>
        </div>
        @foreach($ad as $us)
        <div class="grid-admin-layout grid_table_tb_shared {{ $us->account_lock == 0 ? 'locked' : '' }}">
            <div class="name-user">
                <div class="left">
                    <img src="{{ asset($us->avatar) }}" alt="avatar" class="avatar"
                        onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                </div>
                <div class="right">
                    <div class="right-tren">{{$us->last_name}} {{$us->first_name}} </div>
                    <div class="right-duoi">{{$us->email}}</div>
                </div>
            </div>
            <div class="ct">
                @if($us->dob)
                {{\Carbon\Carbon::parse($us->dob)->format('d/m/Y')}}
                @else
                <p style="opacity: 0.4">Chưa có</p>
                @endif
            </div>
            <div class="ct">{{$us->gender == 1 ? 'Nam' : ($us->gender == 2 ? 'Nữ' : 'Chưa xác định')}}</div>
            <div class="ct" style="text-align: center">
                @if($us->email_verified_at)
                {{$us->email_verified_at->format('d/m/Y')}} <br>
                {{$us->email_verified_at->format('H:i:s')}}
                @else
                <p style="opacity: 0.4">Chưa có</p>
                @endif
            </div>
            <div class="ct">{{$us->roles == 2 ? 'Admin' : ($us->roles == 3 ? 'Nhân viên' : 'Chưa có')}}</div>
            <div class="ct">{{$us->phone}}</div>
            <div class="usad">{{$us->address}}</div>
            <form action="{{route('user.block')}}" id="changeUserLock" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="id" id="idUserInput">
            </form>
            <div class="actions-d  ct">
                <a href="{{$us->id != Auth::id() ? route('dtad', ['id' => $us->id]) : route('profile') }}"
                    class="border-green hover_scale_btn" title="Xem chi tiết">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @if($us->id == Auth::id())
                <a href="{{route('profile.edit')}}" class="border-yellow hover_scale_btn" title="Sửa nhân viên">
                    <i class="fa-regular fa-pen-to-square"></i>
                </a>
                @else
                <button class="EditAdmin border-yellow hover_scale_btn" title="Sửa nhân viên"
                    data-user_id="{{ $us->id }}"><i class="fa-regular fa-pen-to-square"></i></button>
                @endif
                @if(Auth::id() == $us->id)
                <button class="acc-disable border-red hover_scale_btn"
                    title="{{$us->account_lock ? 'Nhấn để khóa' : 'Nhấn để mở'}}">
                    <i class="fa-solid {{$us->account_lock  ? 'fa-unlock' : 'fa-lock'}}"></i>
                    {{-- @if($us->account_lock == 1)
                    <p>Khóa</p>
                    @else
                    <p>Mở</p>
                    @endif --}}
                </button>
                @elseif(Auth::id() != $us->id)
                <button class="acc-lock-user border-red" data-id-user="{{$us->id}}">
                    <i class="fa-solid {{$us->account_lock  ? 'fa-unlock' : 'fa-lock'}}"></i>
                    @if($us->account_lock == 1)
                    <p>Khóa</p>
                    @else
                    <p>Mở</p>
                    @endif
                </button>
                @endif
            </div>
        </div>
        @endforeach
        @if ($ad->lastPage() > 1)
        <ul class="phantrag">
            <li class="{{$pageht}} == 1 ? 'disable' : ''">
                <a href="{{$ad->url($pageht - 1)}}"><i class="fa-solid fa-arrow-left"></i></a>
            </li>
            @if ($start > 1)
            <li><a href="{{$ad->url(1)}}">1</a></li>
            @if($start > 2)
            <li class="disabled"><span>...</span></li>
            @endif
            @endif

            @for ($i = $start; $i <= $end; $i++) <li class="{{($pageht == $i) ? 'active' : ''}}">
                <a href="{{$ad->url($i)}}">{{$i}}</a>
                </li>
                @endfor
                @if ($end < $lapa) @if ($end < $lapa - 1) <li class="disabled"><span>...</span>
                    </li>
                    @endif
                    <li><a href="{{$ad->url($lapa)}}">{{$lapa}}</a></li>
                    @endif

                    <li class="{{($pageht == $lapa) ? 'disabled' : ''}}"><a href="{{$ad->url($pageht + 1)}}"><i
                                class="fa-solid fa-arrow-right"></i></a></li>

        </ul>
        @endif
    </section>
</main>
<script src="{{ asset('/js/Alerts.js') }}"></script>
@endsection