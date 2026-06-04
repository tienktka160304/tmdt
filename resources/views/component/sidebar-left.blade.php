<header>
    <img src="/img/logob.png" alt="logo" class="logo">
</header>
<nav>
    <ul class="sildebar" id="menul">
        <li>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fa fa-home"></i>
                <span>Trang chủ</span>
            </a>
        </li>
        <li>
            <a href="{{ route('page.product.sanpham') }}"
                class="{{ request()->routeIs('page.product.sanpham') ? 'active' : '' }}">
                <i class="fa fa-box"></i>
                <span> Sản phẩm</span>
            </a>
        </li>
        <li>
            <a href="{{ route('donhang', ['orderquery' => 'all']) }}"
                class="{{ request()->routeIs('donhang') ? 'active' : '' }}">
                <i class="fa fa-shopping-cart"></i>
                <span>Đơn hàng</span>
            </a>
        </li>
        
        {{-- ĐÃ SỬA: Bọc Auth::check() để chống lỗi khi chưa đăng nhập --}}
        @if (Auth::check() && Auth::user()->roles == 2)
            <li>
                <a href="{{ route('user') }}" class="{{ request()->routeIs('user') ? 'active' : '' }}">
                    <i class="fa fa-users"></i>
                    <span>Người dùng</span>
                </a>
            </li>
        @endif
        
        <li>
            <a href="{{ route('baiviet') }}" class="{{ request()->routeIs('baiviet') ? 'active' : '' }}">
                <i class="fa fa-newspaper"></i>
                <span>Bài viết</span>
            </a>
        </li>
        <li>
            <a href="{{ route('catepost') }}" class="{{ request()->routeIs('catepost') ? 'active' : '' }}">
                <i class="fa-solid fa-list"></i>
                <span>Danh mục bài viết</span>
            </a>
        </li>
        <li>
            <a href="{{ route('user_review.show') }}"
                class="{{ request()->routeIs('user_review.show') ? 'active' : '' }}">
                <i class="fa-solid fa-user-pen"></i>
                <span>Đánh giá</span>
            </a>
        </li>
        <li>
            <a href="{{ route('khuyenmai') }}" class="{{ request()->routeIs('khuyenmai') ? 'active' : '' }}">
                <i class="fa fa-tags"></i>
                <span>Khuyến mãi</span>
            </a>
        </li>
        <li>
            <a href="{{ route('page.category.index') }}"
                class="{{ request()->routeIs('page.category.index') ? 'active' : '' }}"> 
                <i class="fa-solid fa-list"></i>
                <span>Danh mục</span>
            </a>
        </li>
        <li>
            <a href="{{ route('page.brand.index') }}"
                class="{{ request()->routeIs('page.brand.index') ? 'active' : '' }}">
                <i class="fa-solid fa-trademark"></i>
                <span>Thương hiệu</span>
            </a>
        </li>
        <li>
            <a href="{{ route('page.comment.binhluan') }}"
                class="{{ request()->routeIs('page.comment.binhluan') ? 'active' : '' }}">
                <i class="fa fa-comments"></i>
                <span>Bình luận</span>
            </a>
        </li>
        <li>
            <a href="{{ route('banner.show', ['banner' => 'all']) }}"
                class="{{ request()->routeIs('banner.show') ? 'active' : '' }}">
                <i class="fa fa-image"></i>
                <span>Banner</span>
            </a>
        </li>
        <li>
            <a href="{{ route('payments.index') }}"
                class="{{ request()->routeIs('payments.index') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check-dollar"></i>
                <span>Thanh toán</span>
            </a>
        </li>
        
        {{-- ĐÃ SỬA: Bọc Auth::check() --}}
        @if (Auth::check() && Auth::user()->roles == 2)
            <li>
                <a href="{{ route('admin') }}" class="{{ request()->routeIs('admin') ? 'active' : '' }}">
                    <i class="fa fa-user-tie"></i>
                    <span>Nhân viên</span>
                </a>
            </li>
        @endif
    </ul>
</nav>