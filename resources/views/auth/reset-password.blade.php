<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        body { font-family:serif, display: flex;  sans-serif; text-align: center; padding: 50px; }
        form { display: inline-block; text-align: left; padding: 20px; border: 1px solid #ccc; border-radius: 5px; background: #f9f9f9; }        h2 { color: #333; }
        input { display: block; width: 300px; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        label { margin-bottom: 5px; font-weight: bold; }
        button { padding: 10px 20px; background: #0a402b; cursor: pointer; width: 100%; border-radius: 4px; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Đặt Lại Mật Khẩu</h2>
    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <label>Mật khẩu mới:</label>
        <input type="password" name="password" required>

        <label>Xác nhận mật khẩu:</label>
        <input type="password" name="password_confirmation" required>

        <button type="submit">Đặt lại mật khẩu</button>
    </form>
</body>
</html>
