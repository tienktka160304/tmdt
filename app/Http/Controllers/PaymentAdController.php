<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentAdController extends Controller
{

    public function index()
    {
        $payments = Payment::all();
        return view('page.payment.index', compact('payments'));
    }

    // Hiển thị form tạo mới
    public function create()
    {
        return view('page.payment.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            // 'bank_number' => 'nullable|numeric',
            'status' => 'required|in:0,1',
        ], [
            'payment_method.required' => 'Vui lòng nhập phương thức thanh toán.',
            // 'bank_number.numeric' => 'Số tài khoản phải là số.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        // Lưu vào DB
        Payment::create($request->only('payment_method', 'bank', 'bank_number', 'status'));

        return redirect()->back()->with('success', 'Thêm phương thức thanh toán thành công!');
    }

    public function edit($id)
    {
        $payment = Payment::find($id);
        if (!$payment) {
            return redirect()->route('payments.index')->with('error', 'Payment not found');
        }

        return view('page.payment.edit', compact('payment'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            'bank_number' => 'nullable|string|max:255',
            'status' => 'required|integer',
        ]);

        $payment = Payment::findOrFail($id);
        if (!$payment) {
            return redirect()->route('payments.index')->with('error', 'Không tìm thấy phương thức thanh toán');
        }
        $payment->payment_method = $request->input('payment_method');
        $payment->bank = $request->input('bank');
        $payment->bank_number = $request->input('bank_number');
        $payment->status = $request->input('status');
        $payment->save();

        return redirect()->route('payments.index')->with('success', 'Cập nhật phương thức thanh toán thành công!');
    }

    public function toggleStatus($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = !$payment->status;
        $payment->save();

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái phương thức thanh toán.');
    }
}
