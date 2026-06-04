@extends('layout')

@section('title', 'Chi tiết đơn hàng')

@section('content')
{{-- code mới --}}
<main class="container-hd">
    <div class="column-left">
        <div class="order-id-product">
            <div class="product-info">
                <div class="product-header">
                    <span class="product-id"><i class="fas fa-box"></i> id: # {{$order->id}}</span>
                    {{-- <span class="status processed"><i class="fas fa-check-circle"></i>{{$status_label}}</span> --}}
                    <span class="status processed" style="{{ $status['style'] }}">
                        <i class="fas fa-check-circle"></i> {{ $status['label'] }}
                    </span>

                </div>
                <span class="process-date"><i class="fas fa-calendar-alt"></i> {{$order->order_date}}</span>
            </div>
            {{-- <div class="order-btn-status">
                <button>thay đổi trạng thái</button>
            </div> --}}
        </div>
        {{-- --}}

        <div class="order-information">
            {{-- test --}}
            <div class="detail-pot">
                <div class="profile-content">
                    <div class="profile-header">
                        <i class="fas fa-user"></i>
                        <h2>Khách hàng</h2>
                    </div>
                    <div class="profile-right">
                        <div class="info-grid">
                            <span class="info-label">Họ và tên:</span>
                            <span>{{$order->user->first_name}} {{$order->user->last_name}}</span>
                            <span class="info-label">Gmail:</span>
                            <span>{{$order->user->email}}</span>
                            <span class="info-label">Số điện thoại:</span>
                            <span>{{$order->user->phone}}</span>
                            <span class="info-label">Địa chỉ:</span>
                            <span>{{$order->user->address}}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <a class="profile-button" href="{{route('dtus', ['id' => $order->id_user])}}">
                        Xem hồ sơ
                    </a>
                </div>

            </div>
            {{-- --}}

            <div class="detail-pot">
                <div class="profile-content">
                    <div class="profile-header">
                        <i class="fa-solid fa-user-tag"></i>
                        <h2>Thông tin đặt hàng</h2>
                    </div>
                    <div class="info-grid">
                        <span class="info-label">Phương thức thanh toán:</span>
                        <span>{{$order->payment->payment_method}}</span>
                        <span class="info-label">Trạng thái:</span>
                        <span class="address-line">{{$thanh_toan}} </span>

                    </div>
                </div>
                <div class="ghi-chus">
                    <span class="info-labell">Ghi chú:</span>
                    <span class="ghichu">{{$order->note}}</span>
                </div>
            </div>
            <div class="detail-pot">
                <div class="profile-content">
                    <div class="profile-header">
                        <i class="fa-solid fa-user-check"></i>
                        <h2>Thông tin nhận hàng</h2>
                    </div>
                    <div class="info-grid">
                        <span class="info-label">Đơn hàng được tạo:</span>
                        <span>{{$order->order_date}}</span>
                        <span class="info-label">Họ và tên:</span>
                        <span>{{$order->user->first_name}} {{$order->user->last_name}}</span>
                        <span class="info-label">Gmail:</span>
                        <span>{{$order->user->email}}</span>
                        <span class="info-label">Số điện thoại:</span>
                        <span>{{$order->phone}}</span>
                        <span class="info-label">Địa chỉ:</span>
                        <span>{{$order->address}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cột phải -->
    <div class="column-right">
        <div class="order-container">
            <h2>Danh sách sản phẩm</h2>

            <div class="order-page">
                <table class="order-table">
                    <thead class="order-table-header">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Order ID</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                        </tr>
                    </thead>
                    <tbody class="order-table-body">
                        @foreach ($order->orders_detail as $item)
                        <tr>
                            <td class="order-cell">{{$item->productVariant->product->name}}</td>
                            <td class="order-cell">#{{$order->id}}</td>
                            <td class="order-cell">{{$item->quantity}}</td>
                            <td class="order-cell">{{number_format($item->productVariant->price, 0, ',', '.')}}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            <!-- Tổng hợp thanh toán -->
            <div class="order-summary">
                <div class="total"><span>Tổng</span> <span>{{number_format($order->total_price , 0, ',', '.')}}đ</span>
                </div>
            </div>
        </div>

    </div>
</main>

@endsection