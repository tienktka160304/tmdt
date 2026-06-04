@extends('layout')

@section('title', 'THÔNG TIN TÀI KHOẢN')
@section('content')
<main>
    <div class="container-ad">
        <div class="lef">
            <img src="{{ asset($ad->avatar) }}" alt="" onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
        </div>
        <div class="center">
            <div class="center-1">
                {{$ad->first_name}} {{$ad->last_name}}
            </div>
            <div class="center-2">
                id:{{$ad->id}}
            </div>
        </div>
        <div class="rig">
            <div class="rig1">
                <div class="rig1-1">
                    <span>Trạng Thái :</span>
                    <div class="{{$ad->account_lock == 1 ? 'x' : 'red'}}">{{$ad->account_lock == 1 ? 'Hoạt động' : 'Đang
                        khóa'}}</div>
                </div>
                <div class="rig1-2">
                    <div class="contai">
                        <div class="contai-left">
                            <span>Ngày sinh :</span>
                            @if($ad->dob)
                            {{\Carbon\Carbon::parse($ad->dob)->format('d/m/Y')}}
                            @else
                            <p style="opacity: 0.4">Chưa có</p>
                            @endif
                        </div>
                        <div class="contai-right">
                            <span>Cấp :</span>
                            <div>{{$ad->roles == 2 ? 'Quản lý' : 'Nhân viên'}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rig1">
                <div class="rig1-1">
                    <span>Số điện thoại :</span>
                    <div>{{$ad->phone}}</div>
                </div>
                <div class="rig1-2">
                    <span>Email :</span>
                    <div>{{$ad->email}}</div>
                </div>
            </div>
            <div class="rig1">
                <div class="rig1-1">
                    <span>Giới tính :</span>
                    <div>{{$ad->gender == 1 ? 'Nam' : ($ad->gender == 2 ? 'Nữ' : 'Khác')}}</div>
                </div>
                <div class="rig1-2">
                    <span>Địa chỉ :</span>
                    <div>{{$ad->address}}</div>
                </div>
            </div>
            <div class="rig1">
                <div class="rig1-1">
                    <span>Tạo lúc :</span>
                    <div>{{$ad->created_at->format('d/m/Y H:i:s')}}</div>
                </div>
                <div class="rig1-2">
                    <span>Cập nhật lúc :</span>
                    <div>{{$ad->updated_at->format('d/m/Y H:i:s')}}</div>
                </div>
            </div>
            <div class="rig1">
            </div>
        </div>
    </div>
</main>
@endsection