@extends('layout')

@section('title', 'THAY ĐỔI MẬT KHẨU')

@section('content')
<!--  -->

<main>
    <div class="form-center">
        <form class="edit_pass" action="{{ route('changePass') }}" method="POST" id="checkpass">
            @csrf
            <div class="pass-top">
                <div class="pass-1">
                    <div class="left">
                        <img src="{{asset($ad->avatar)}}" alt="Lỗi...">

                    </div>
                    <div class="right">
                        <div class="right-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }} </div>
                        <div class="right-email">id:{{ Auth::user()->id }}</div>
                        <div class="right-email">{{Auth::user()->email}}</div>
                    </div>
                </div>
                <div class="pass-center">
                    <label>Mật khẩu hiện tại:</label>
                    <input type="password" name="current_password" placeholder="Nhập mật khẩu">
                </div>
                <div class="pass-error">
                    @error('current_password')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pass-center">
                    <label>Mật khẩu mới:</label>
                    <input type="password" id="pw-cre" name="password" placeholder="Nhập lại mật khẩu">
                </div>
                <div class="pass-error">
                    @error('password')
                    <span id="erop1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="pass-center">
                    <label>Nhập lại mật khẩu mới:</label>
                    <input type="password" id="pwcf-cre" placeholder="Nhập lại mật khẩu mới">
                </div>
                <div class="pass-error">
                    <span id="erop"></span>
                </div>
                <div class="pass-submit">
                    <button type="submit">Đổi mật khẩu</button>
                </div>
            </div>
            <div class="pass-top">
            </div>
            <div class="container">

                <div class="pass">
                </div>
                <div class="pass1">
                    <label></label>

                </div>
            </div>
            <div class="container">
                <div class="pass">

                </div>
                <div class="pass">

                </div>
                <div class="pass">

                </div>
            </div>
        </form>
    </div>

</main>
@endsection