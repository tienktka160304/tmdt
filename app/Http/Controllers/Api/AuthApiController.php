<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;

class AuthApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'access_token' => User::where('roles', '=', 1)->get(),
            'token_type' => 'Bearer',
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Kiểm tra email để đặt lại mật khẩu'])
            : response()->json(['message' => 'Email không tồn tại!'], 404);
    }

    public function getUserActive(Request $request)
    {
        $user = User::where("id", $request->id)->get();
        return response()->json([
            'data' => $user,
        ]);
    }

    public function register(Request $request)
    {
        // 1. Kiểm tra xem email đã tồn tại trong hệ thống chưa
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            // Nếu đã tồn tại nhưng CHƯA xác thực -> Gửi lại mail xác nhận
            if (!$existingUser->hasVerifiedEmail()) {
                $existingUser->sendEmailVerificationNotification();
                return response()->json([
                    'message' => 'Email này đã đăng ký nhưng chưa xác thực. Hệ thống đã gửi lại link xác nhận vào hộp thư của bạn!',
                ], 200);
            }
            // Nếu đã xác thực rồi -> Để Validator bên dưới báo lỗi "Email đã tồn tại"
        }

        // 2. Validation chính thức cho tài khoản mới
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            // Trả về lỗi chi tiết đầu tiên để giao diện React dễ hiển thị
            $error = $validator->errors()->first();
            
            if ($validator->errors()->has('email')) $error = 'Email không hợp lệ hoặc đã được sử dụng!';
            if ($validator->errors()->has('password')) $error = 'Mật khẩu phải có ít nhất 8 ký tự!';

            return response()->json([
                'errors' => $error,
            ], 422);
        }

        // 3. Tạo user mới
        $user = User::create([
            'email' => $request->email,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // 4. Kích hoạt sự kiện gửi mail xác thực (BẮT BUỘC)
        event(new Registered($user));

        return response()->json([
            'message' => 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản.',
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Kiểm tra tài khoản có tồn tại không
        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Thông tin đăng nhập không chính xác!'], 401);
        }

        // Kiểm tra khóa tài khoản (account_lock = 0 là bị khóa)
        if ($user->account_lock == 0) {
            return response()->json(['message' => 'Tài khoản của bạn đã bị khóa!'], 401);
        }

        // Kiểm tra đã xác thực email chưa
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Vui lòng xác thực email trước khi đăng nhập!',
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Đã đăng xuất thành công!']);
    }

    public function updateUser(Request $request)
    {
        try {
            $user = auth()->user();
            
            if ($request->hasFile('img')) {
                $request->validate(['img' => 'image|mimes:jpg,jpeg,png|max:2048']);
                $image = $request->file('img');
                $imageName = time() . '.' . $image->extension();
                $image->move(public_path('/img/user'), $imageName);
                $user->avatar = '/img/user/' . $imageName;
            }

            if ($request->filled('phone')) $user->phone = $request->input('phone');
            if ($request->filled('gender')) $user->gender = $request->input('gender');
            if ($request->filled('address')) $user->address = $request->input('address');
            
            if ($request->filled('dob')) {
                $validator = Validator::make($request->all(), ['dob' => 'date|before_or_equal:today']);
                if ($validator->fails()) {
                    return response()->json(['errors' => 'Ngày sinh không hợp lệ!'], 422);
                }
                $user->dob = $request->input('dob');
            }

            $user->save();

            return response()->json([
                'message' => 'Cập nhật thông tin thành công!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Mật khẩu hiện tại không đúng.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Mật khẩu đã được thay đổi thành công!',
        ]);
    }
}