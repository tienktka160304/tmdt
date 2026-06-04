<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('/css/login.css') }}" rel="stylesheet" />
    <title>Login</title>
    </head>

<body>
    <main>
        <section class="mri">
            <form method="POST" action="{{ route('login') }}" role="form">
                <h1>Đăng nhập</h1>
                @csrf
                
                @if (session('error'))
                    <div style="color: red; margin-bottom: 10px; text-align: center;">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="un">
                    <label for="email">Email:</label><br>
                    <input type="text" id="email" placeholder="Nhập Email" name="email" value="{{ old('email') }}">
                </div>
                @if ($errors->has('email'))
                    <span class="error-message" style="color: red; font-size: 12px;">{{ $errors->first('email') }}</span>
                @endif

                <div class="pn">
                    <label for="password">Mật khẩu:</label><br>
                    <input type="password" id="password" placeholder="Nhập mật khẩu" name="password">
                </div>
                @if ($errors->has('password'))
                    <span class="error-message" style="color: red; font-size: 12px;">{{ $errors->first('password') }}</span>
                @endif

                <button type="submit" style="margin-top: 20px;">Login</button>
            </form>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Lỗi...',
            text: '{{ session('error') }}',
        })
    </script>
    @endif
</body>
</html>