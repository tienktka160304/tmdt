@extends('layout')

@section('title', 'Cập nhật Tài Khoản')

@section('content')
<!--  -->

<main>
    <form class="edit-admin" action="{{ route('profile.up') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="sec1">
            <input id="file" type="file" accept="image/*" name="avatar" onchange="previImg(event)">
            <label for="file">
                @if($ad->avatar)
                <div id="imgpre-ad" style="margin-top: 10px;">
                    <img src="{{ asset($ad->avatar) }}" alt="Ảnh hiện tại">
                    <span class="plus-icon"><i class="fa-solid fa-plus"></i></span>
                </div>
                @endif
            </label>
            <span>#id:{{ Auth::user()->id }}</span>
            @error('avatar')
            <span id="erop1">{{ $message }}</span>
            @enderror
        </div>
        <div class="sec-cen">
            <label>Họ và Tên :</label>
            <input type="text" name="last_name" placeholder="Nhập họ" value="{{Auth::user()->last_name}}">
            <input type="text" name="first_name" placeholder="Nhập tên" value="{{Auth::user()->first_name}}">
            @error('first_name')
            <span id="erop1">{{ $message }}</span>
            @enderror
            @error('last_name')
            <span id="erop1">{{ $message }}</span>
            @enderror
        </div>
        <div class="sec2">
            <div class="sec22">
                <div class="sec2-1">
                    <span class="m100">Trạng Thái :</span>
                    <div class="{{$ad->account_lock == 1 ? 'x' : 'red'}}">{{ Auth::user()->account_lock == '1' ? 'Hoạt
                        động' : 'Đang khóa' }}</div>
                </div>
                <div class=" sec2-2">
                    <div class="sec2-22">
                        <div class="left">
                            <label>Ngày sinh:</label>
                            <input type="date" name="dob" max="{{date('Y-m-d')}}" value="{{$ad->dob}}">
                        </div>
                        <div class="right">
                            <span>Cấp :</span>
                            <div>{{ Auth::user()->roles == 2 ? 'Quản lý' : (Auth::user()->roles == 3 ? 'Nhân viên' : 'Chưa xác
                        định' ) }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sec22">
                <div class="sec2-1">
                    <label class="m100">Số điện thoại:</label>
                    <input type="phone" name="phone" placeholder="Nhập SĐT" value="{{Auth::user()->phone}}">
                </div>
                <div class=" sec2-2">
                    <label>Email:</label>
                    <input type="email" name="email" placeholder="Nhập Email" value="{{Auth::user()->email}}">
                </div>
            </div>
            <div class="sec-er">
                <div class="sec2-1">
                    @error('phone')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                </div>
                <div class=" sec2-2">
                    @error('email')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="sec22">
                <div class="sec2-1">
                    <label class="m100">Giới tính:</label>
                    <select name="gender">
                        <option value="1" {{ Auth::user()->gender == '1' ? 'selected' : '' }}>Nam</option>
                        <option value="2" {{ Auth::user()->gender == '2' ? 'selected' : '' }}>Nữ</option>
                    </select>
                </div>
                <div class=" sec2-2">
                    <label>Địa chỉ:</label>
                    <input type="address" name="address" placeholder="Nhập địa chỉ" value="{{Auth::user()->address}}">
                </div>
            </div>
            <div class="sec-er">
                <div class="sec2-1">
                </div>
                <div class=" sec2-2">
                    @error('address')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="sec22">
                <div class="sec2-1">
                    <span class="m100">Cập nhật lúc:</span>
                    <div>{{Auth::user()->updated_at->format('d-m-Y H:i:s')}}</div>
                </div>
                <div class="sec2-2">
                    <span>Thời gian tạo:</span>
                    <div>{{Auth::user()->created_at->format('d-m-Y H:i:s')}}</div>
                </div>
            </div>
        </div>
        <div class="sec4">
            <button type="submit">Cập nhật</button>
        </div>
        </div>
    </form>
</main>
@endsection