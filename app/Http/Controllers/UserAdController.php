<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ChangePassRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class UserAdController extends Controller
{

    public function __construct()
    {
        $this->middleware('roles:2')->only(['index', 'blockuser', 'detail_us', 'admin', 'edit_ad', 'update_ad', 'create_ad', 'store_ad', 'detail_ad']);
    }
    public function index(Request $request)
    {
        $query = User::where('roles', 1);

        // Lọc theo query
        if ($request->query('query') == 'user_unverified') {
            $query->whereNull('email_verified_at');
        } elseif ($request->query('query') == 'near_birthday') {
            $query->whereMonth('dob', now()->month)->whereDay('dob', '>=', now()->day)->whereDay('dob', '<=', now()->day + 7);
        }

        // Tìm kiếm
        if ($request->has('key')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->key . '%')
                    ->orWhere('last_name', 'like', '%' . $request->key . '%')
                    ->orWhere('email', 'like', '%' . $request->key . '%');
            });
        }

        // Sắp xếp theo thời gian tạo tài khoản
        if ($request->filled('filter')) {
            switch ($request->input('filter')) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc'); // Mặc định: mới nhất
        }

        // Phân trang
        $show = $request->show ?? 10;
        $dhus = $query->paginate($show);
        $dhus->appends($request->all());
        $pageht = $dhus->currentPage();
        $lapa = $dhus->lastPage();
        $start = max(1, $pageht - 2);
        $end = min($lapa, $pageht + 2);

        return view('page.user.user', compact('dhus', 'pageht', 'lapa', 'start', 'end'));
    }

    public function blockuser(Request $request)
    {
        $idUs = $request->input('id');
        $idUser = User::find($idUs);
        if ($idUser) {
            $idUser->account_lock = $idUser->account_lock == 1 ? 0 : 1;
            $idUser->save();
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function detail_us(Request $request, $id)
    {
        $user = User::find($id);
        $orderquery = Order::with([
            'payment',
            'orders_detail.productVariant.product.discount'
        ])->where('id_user', $id);

        if ($request->has('orderquery')) {
            switch ($request->query('orderquery')) {
                case 0:
                    $orderquery->where('status', 0);
                    break;
                case 3:
                    $orderquery->where('status', 3);
                    break;
                case 'all':
                    $orderquery->orderBy('order_date', 'DESC');
                    break;
            }
        }
        if ($key = request()->key) {
            $orderquery->where(function ($tim) use ($key) {
                $tim->where('orders.id', 'like', '%' . $key . '%');
            });
        }
        switch ($thutu = request()->thutu) {
            case 1:
                $orderquery->orderBy('status', 'DESC');
                break;
            case 2:
                $orderquery->orderBy('total_price', 'DESC');
                break;
            default:
                $orderquery->orderBy('order_date', 'DESC');
                break;
        }
        $order = $orderquery->get();
        $dht = Order::where('id_user', $id)->count();
        $dhht = Order::where('id_user', $id)->where('status', 3)->count();
        $dhhuy = Order::where('id_user', $id)->where('status', 0)->count();
        return view('page.user.detail', compact('dht', 'order', 'user', 'dhht', 'dhhuy'));
    }
    public function admin(Request $request)
    {
        $sotrang = 20;
        $thutu = $request->input('thutu');
        $key = $request->input('key');
        $adque = User::where('roles', '!=', 1);
        if (!empty($key)) {
            $adque->where(function ($tim) use ($key) {
                $tim->where('email', 'like', '%' . $key . '%')
                    ->orwhere('id', 'like', '%' . $key . '%')
                    ->orwhere('last_name', 'like', '%' . $key . '%')
                    ->orwhere('first_name', 'like', '%' . $key . '%')
                    ->orWhere('phone', 'like', '%' . $key . '%');
            });
        }
        switch ($thutu) {
            case 1:
                $adque->orderBy('created_at', 'ASC');
                break;
            case 2:
                $adque->orderBy('roles', 'ASC');
                break;
            default:
                $adque->orderBy('created_at', 'desc');
                break;
        }
        $ad = $adque->paginate($sotrang);
        $pageht = $ad->currentPage();
        $lapa = $ad->lastPage();
        $start = max($pageht - 1, 1);
        $end = min($pageht + 1, $lapa);
        return view('page.admin.admin', compact('ad', 'thutu', 'start', 'end', 'pageht', 'lapa'));
    }
    public function edit_ad($id)
    {
        $ad = User::where('id', $id)->where('roles', '!=', 1)->first();
        if (!$ad) {
            return redirect()->back()->with('error', 'Người dùng không tồn tại.');
        }
        return view('page.admin.edit', compact('ad'));
    }
    public function update_ad(ProfileRequest $request, $id)
    {
        $ad = User::findOrFail($id);
        if ($request->hasFile('avatar')) {
            if ($ad->avatar && file_exists(public_path($ad->avatar))) {
                unlink(public_path($ad->avatar));
            }
            $image = $request->file('avatar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/img/user'), $imageName);
            $ad->avatar = '/img/user/' . $imageName;
        }
        $ad->update([
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'roles' => $request->input('roles'),
            'account_lock' => $request->input('account_lock'),
            'dob' => $request->input('dob'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'updated_at' => now(),
        ]);
        $ad->save();
        return redirect()->route('admin')->with('success', 'Cập nhật tài khoản ' . $id . ' thành công!');
    }
    public function create_ad()
    {
        return view('page.admin.create');
    }
    public function up_roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->roles = $request->roles;
        $user->save();
        return redirect()->route('user')->with('success', 'Đã Cấp Quyền thành công!');
    }
    public function detail_ad($id)
    {
        $ad = User::where('id', $id)->where('roles', '!=', 1)->first();
        if (!$ad) {
            return redirect()->back()->with('error', 'Người dùng không tồn tại.');
        }
        return view('page.admin.detail', compact('ad'));
    }
    public function profile_ad()
    {
        $ad = Auth::user();
        return view('page.admin.profile', compact('ad'));
    }
    public function up_profile_ad()
    {
        $ad = Auth::user();
        return view('page.admin.update_profile', compact('ad'));
    }
    public function str_profile_ad(ProfileRequest $request)
    {
        $ad = User::findOrFail(Auth::id());
        if ($request->hasFile('avatar')) {
            if ($ad->avatar && file_exists(public_path($ad->avatar))) {
                unlink(public_path($ad->avatar));
            }
            $image = $request->file('avatar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('/img/user'), $imageName);
            $ad->avatar = '/img/user/' . $imageName;
        }
        $ad->update([
            'last_name' => $request->input('last_name'),
            'first_name' => $request->input('first_name'),
            'email' => $request->input('email'),
            'dob' => $request->input('dob'),
            'phone' => $request->input('phone'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'updated_at' => now(),
        ]);
        $ad->save();
        return redirect()->route('profile')->with('success', 'Cập nhật tài khoản thành công!');
    }
    public function ShowchangePass()
    {
        $ad = Auth::user();
        return view('page.admin.changePass', compact('ad'));
    }
    public function changePass(ChangePassRequest $request)
    {
        /** @var \App\Models\User $ad */
        $ad = Auth::user();
        if (!Hash::check($request->current_password, $ad->password)) {
            return redirect()->back()->with('error', 'Mật khẩu không đúng!');
        }
        $ad->password = Hash::make($request->password);
        $ad->save();
        return redirect()->route('profile')->with('success', 'Mật khẩu thay đổi thành công.');
    }
}
