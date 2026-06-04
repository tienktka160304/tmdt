<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderSuccessMail;

class CheckOutApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function paymethod()
    {
        $payments = Payment::where('status', 1)->get();

        return response()->json([
            'success' => true,
            'payments' => $payments
        ], 200);
    }

    public function getOrderById(Request $request)
    {
        $order = Order::where('id', $request->id)
            ->where('id_user', Auth::id())
            ->with('payment')
            ->where('status', '>', 0)
            ->first();

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đơn hàng không tồn tại.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $order
        ]);
    }


    //put order 
    public function order(Request $request)
    {
        $validated = $request->validate([
            'id_payment' => 'required|exists:payments,id',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'note' => 'nullable|string|max:500',
            'email' => 'nullable|email',
            'name' => 'nullable|string|max:255',
            'order_details' => 'required|array|min:1',
            'order_details.*.id_variant' => 'required|exists:product_variants,id',
            'order_details.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $totalProductPrice = 0;
                $orderDetailsData = [];

                foreach ($request->order_details as $detail) {
                    $variant = ProductVariant::with('product.activeDiscount')
                        ->lockForUpdate()
                        ->findOrFail($detail['id_variant']);

                    if ($variant->stock < $detail['quantity']) {
                        throw new \Exception('Sản phẩm "' . optional($variant->product)->name . '" không đủ tồn kho.');
                    }

                    $price = $variant->price;

                    if (
                        $variant->product &&
                        $variant->product->activeDiscount
                    ) {
                        $discountValue = $variant->product->activeDiscount->value;
                        $price = $price - ($price * $discountValue / 100);
                    }

                    $price = round($price);
                    $quantity = $detail['quantity'];
                    $totalProductPrice += $price * $quantity;

                    $orderDetailsData[] = [
                        'id_variant' => $variant->id,
                        'price' => $price,
                        'quantity' => $quantity,
                    ];
                }

                $shippingFee = 0;

                if ($totalProductPrice < 10000000) {
                    if (str_contains(strtolower($request->address), 'hồ chí minh') || str_contains(strtolower($request->address), 'tp.hcm')) {
                        $shippingFee = 30000;
                    } else {
                        $shippingFee = 60000;
                    }
                }

                $totalPrice = $totalProductPrice + $shippingFee;

                $order = Order::create([
                    'id_user' => Auth::id(),
                    'id_payment' => $request->id_payment,
                    'note' => $request->note,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'total_price' => $totalPrice,
                    'status' => 1,
                    'thanh_toan' => 0
                ]);

                foreach ($orderDetailsData as $detail) {
                    OrderDetail::create([
                        'id_order' => $order->id,
                        'id_variant' => $detail['id_variant'],
                        'price' => $detail['price'],
                        'quantity' => $detail['quantity'],
                    ]);

                    ProductVariant::where('id', $detail['id_variant'])
                        ->decrement('stock', $detail['quantity']);
                }

                return $order;
            });

            try {
                $inFoOrder = Order::with('orders_detail.productVariant.product')->find($order->id);

                if ($request->email) {
                    Mail::to($request->email)->send(new OrderSuccessMail($inFoOrder, $request->name));
                }
            } catch (\Exception $e) {
                \Log::error("Lỗi gửi email đơn hàng " . $order->id . ": " . $e->getMessage());
            }

            if ($request->id_payment == 4) {
                $vnp_TmnCode = env('VNPAY_TMN_CODE', 'T90W6Q5G');
                $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'YOUR_SECRET_KEY');
                $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
                $vnp_Returnurl = url("/api/vnpay-return");

                $vnp_TxnRef = $order->id;
                $vnp_OrderInfo = "Thanh toan don hang " . $order->id;
                $vnp_OrderType = 'billpayment';
                $vnp_Amount = $order->total_price * 100;
                $vnp_Locale = 'vn';
                $vnp_IpAddr = $request->ip();

                $inputData = [
                    "vnp_Version" => "2.1.0",
                    "vnp_TmnCode" => $vnp_TmnCode,
                    "vnp_Amount" => $vnp_Amount,
                    "vnp_Command" => "pay",
                    "vnp_CreateDate" => date('YmdHis'),
                    "vnp_CurrCode" => "VND",
                    "vnp_IpAddr" => $vnp_IpAddr,
                    "vnp_Locale" => $vnp_Locale,
                    "vnp_OrderInfo" => $vnp_OrderInfo,
                    "vnp_OrderType" => $vnp_OrderType,
                    "vnp_ReturnUrl" => $vnp_Returnurl,
                    "vnp_TxnRef" => $vnp_TxnRef,
                ];

                ksort($inputData);

                $query = "";
                $hashdata = "";
                $i = 0;

                foreach ($inputData as $key => $value) {
                    if ($i == 1) {
                        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                    } else {
                        $hashdata .= urlencode($key) . "=" . urlencode($value);
                        $i = 1;
                    }

                    $query .= urlencode($key) . "=" . urlencode($value) . '&';
                }

                $vnp_Url = $vnp_Url . "?" . $query;

                if (isset($vnp_HashSecret)) {
                    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                    $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Đang chuyển hướng đến VNPay...',
                    'vnpay_url' => $vnp_Url,
                    'order' => $order->id
                ]);
            }

            return response()->json([
                'status' => 'success',
                'order' => $order->id,
                'message' => 'Đặt hàng thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'YOUR_SECRET_KEY');

        $inputData = $request->all();

        if (!isset($inputData['vnp_SecureHash'])) {
            return redirect(env('FRONTEND_URL') . '/checkout-online?status=error');
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);

        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $orderId = $request->input('vnp_TxnRef');

        if ($secureHash === $vnp_SecureHash && $request->input('vnp_ResponseCode') === '00') {
            Order::where('id', $orderId)->update([
                'thanh_toan' => 1
            ]);

            return redirect(env('FRONTEND_URL') . '/checkout-online?id=' . $orderId . '&status=success');
        }

        return redirect(env('FRONTEND_URL') . '/checkout-online?id=' . $orderId . '&status=failed');
    }
    // 
    public function webhook(Request $request)
    {

        if ($request->transferType !== "in") {
            return response()->json(['success' => false, 'message' => 'not order ']);
        }

        $transaction_content = $request->input('content');
        $regex = '/MDH(\d+)/';
        preg_match($regex, $transaction_content, $matches);
        $pay_order_id = $matches[1];

        if (!is_numeric($pay_order_id)) {
            return response()->json(['success' => false, 'message' => 'Order not found. Order_id ']);
        }

        $order = Order::where("id", $pay_order_id)
            ->where('thanh_toan', 0)
            ->where('total_price', $request->transferAmount)->first();


        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found. Order_id ' . $pay_order_id]);
        }
        $order->thanh_toan = 1;
        $order->save();
        return response()->json(['success' => true, 'data' => $order, 'message' => 'Order not found. Order_id '], 200);
    }

    // 
    public function KTThanhToan(Request $request)
    {


        // Tìm đơn hàng Điều kiện là id đơn hàng, số tiền, trạng thái đơn hàng phải là 'Unpaid'
        $order = Order::where("id", $request->id)
            ->where('thanh_toan', 1)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Chưa thanh toán'], 201);
        } else {
            return response()->json(['success' => true, 'message' => 'thanh toán thành cong'], 200);
        }
    }
}