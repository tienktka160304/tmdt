<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    public function __construct() {}
    public function index()
    {
        if (Auth::id() > 1) {
            return redirect()->route('home');
        }
        return view('login');
    }
    public function login(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if (Auth::attempt($credentials)) {
            $u = Auth::user();
            $role = (int)$u->roles;
            if ($role === 1) {
                Auth::logout();
                return redirect()->route('auth')->with('error', 'Bạn không có quyền đăng nhập!');
            }
            if (is_null($u->email_verified_at)) {
                Auth::logout();
                return redirect()->route('auth')->with('error', 'Vui lòng xác thực email trước khi đăng nhập.');
            }
            return redirect()->route('home');
        }
        return redirect()->route('auth')->with('error', 'Email hoặc mật khẩu không đúng!!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth');
    }
}
