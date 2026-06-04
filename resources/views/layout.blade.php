<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/posts.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/order.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/product.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/user.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/admin.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/banner.css') }}" rel="stylesheet" />

    {{-- icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>@yield('title')</title>
</head>
<script src="{{ asset('/js/Alerts.js') }}"></script>
<body>
    {{-- @include('component.header') --}}
    <section class="layout_flex">
        <aside class="layout-left" id="an-nav-left">
            @include('component.sidebar-left')
        </aside>

        <main class="layout-right">
            <header>
                <div class="left">
                    <div class="btn-nav" id="toggleBtn"><i class="fa fa-bars"></i></div>
                    <h1 style="text-transform: uppercase; font-size:20px">@yield('title')</h1>
                </div>

                <div class="right">
                    <div class="tracsion">
                        <div class="noti_donghang">
                            {{-- Đã thêm isset để bọc an toàn tuyệt đối cho biến $OrderCount --}}
                            @if (isset($OrderCount) && $OrderCount > 0)
                                <a href="{{ route('donhang', ['orderquery' => '1']) }}">
                                    <div class="noti_red">
                                        <span>{{ $OrderCount }}</span>
                                    </div>
                                    <i class="fa-regular fa-bell"></i>
                                </a>
                            @else
                                <a href="#">
                                    <i class="fa-regular fa-bell"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- ĐÃ SỬA: Bọc kiểm tra đăng nhập (Auth::check()) để chống sập server (Lỗi 502) khi session hết hạn --}}
                    @if(Auth::check())
                    <div class="acc-container">
                        <div class="first" id="avatar">
                            <img src="{{ asset(Auth::user()->avatar) }}" alt="avatar" class="avatar"
                                onerror="this.onerror=null; this.src='{{ asset('img/user/default-avatar.png') }}';">
                            <p>{{ Auth::user()->first_name }} </p>
                            <i class="fa-solid fa-caret-down"></i>
                        </div>
                        <div class="account-menu" id="accountMenu">
                            <p><strong>{{ Auth::user()->last_name }} {{ Auth::user()->first_name }} </strong></p>
                            <div class="drop-divider"></div>
                            <div>
                                <ul>
                                    <li><a href="{{ route('profile') }}"><i class="fa fa-user"></i>Tài Khoản</a></li>
                                    <li><a href="{{ route('profile.edit') }}"><i class="fa fa-gear"></i>Cập nhật Tài Khoản</a></li>
                                    <li><a href="{{ route('changePass.show') }}"><i class="fa fa-retweet"></i>Thay đổi Mật khẩu</a></li>
                                </ul>
                            </div>
                            <div class="drop-divider"></div>
                            <div>
                                <ul>
                                    <li>
                                        <a href="{{ route('logout') }}"><i class="fa fa-arrow-right-from-bracket"></i>Đăng Xuất</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @else
                        {{-- Hiển thị nút đăng nhập khi session hết hạn, giúp code không bị crash --}}
                        <div class="acc-container">
                            <a href="{{ route('login') ?? '#' }}" style="padding: 8px 15px; color: white; background: #007bff; border-radius: 5px; text-decoration: none; font-size: 14px; display: inline-block;">Đăng nhập</a>
                        </div>
                    @endif
                    
                </div>
            </header>

            <section class="layout-content">
                @yield('content')
            </section>
        </main>

    </section>

</body>
</html>