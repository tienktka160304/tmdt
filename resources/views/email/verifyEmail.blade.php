<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Thành Công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f3f4f6;
            margin: 0;
        }

        .container {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .icon {
            font-size: 50px;
            color: #10b981;
        }

        h1 {
            color: #333;
        }

        p {
            color: #666;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background: #10b981;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn:hover {
            background: #059669;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">✅</div>
        <h1>Xác Nhận Thành Công!</h1>
        <p>{{ $title }}</p>
        <p>Bạn có thể đăng nhập ngay bây giờ.</p>
        <a href="{{ url($frontendUrl . '?adp=true') }}" class="btn">Về trang</a>
    </div>
</body>

</html>