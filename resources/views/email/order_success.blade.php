<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng của bạn đã được đặt thành công</title>
</head>

<body>
    <h2>Xin chào {{ $name }},</h2>
    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi.</p>
    @if($order->id_payment != 1)
    <p>Vui lòng quét mã QR để thanh toán:</p>
    <img src='https://img.vietqr.io/image/{{$order->payment->bank}}-{{$order->payment->bank_number}}-compact2.png?amount={{$order->total_price}}&addInfo=MDH:{{$order->id}}&accountName=NGUYEN TAN PHUOC'
        alt="QR Code" width="400px">
    @else
    <p>Thanh toán tại cửa hàng</p>
    @endif
    <h3>Thông tin thanh toán</h3>
    <p>Mã đơn hàng của bạn: <strong>MDH{{ $order->id }}</strong></p>
    <p>Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }} VND</strong></p>

    <h3>Chi tiết đơn hàng:</h3>
    <ul>
        @foreach($order->orders_detail as $detail)
        <li><strong>{{ $detail->productVariant->product->name }} - Số lượng: {{ $detail->quantity }} - Giá: {{
                number_format($detail->price, 0, ',', '.') }} VND</strong></li>
        @endforeach
    </ul>

    <p><strong>Sau khi xác nhận thanh toán 3 - 4 ngày hàng sẽ được giao đến khách hàng !</strong></p>
</body>

</html>