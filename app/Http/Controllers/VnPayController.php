<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class VnPayController extends Controller
{
    public function createPayment(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.cart')->with('error', 'Giỏ hàng trống');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email',
            'address_line1' => 'required|string|max:255',
            'province' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'age_confirmed' => 'required|boolean',
        ]);

        $total = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

        // Lưu info giao hàng
        Session::put('shipping_info', $request->all());

        // CONFIG
        $vnp_TmnCode    = env('VNP_TMN_CODE');
        $vnp_HashSecret = env('VNP_HASH_SECRET');
        $vnp_Url        = env('VNP_URL');
        $vnp_ReturnUrl  = env('VNP_RETURN_URL');

        $vnp_TxnRef = date('YmdHis') . rand(1000,9999);
        $vnp_Amount = (int)($total * 100);

        $inputData = [
            "vnp_Version"   => "2.1.0",
            "vnp_TmnCode"   => $vnp_TmnCode,
            "vnp_Amount"    => $vnp_Amount,
            "vnp_Command"   => "pay",
            "vnp_CreateDate"=> date('YmdHis'),
            "vnp_ExpireDate"=> date('YmdHis', strtotime('+15 minutes')),
            "vnp_CurrCode"  => "VND",
            "vnp_IpAddr"    => $request->ip(),
            "vnp_Locale"    => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang " . $vnp_TxnRef, // KHÔNG dấu
            "vnp_OrderType" => "billpayment",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef"    => $vnp_TxnRef,
        ];

        // 🔥 SORT
        ksort($inputData);

        // 🔥 BUILD QUERY + HASH (CHUẨN NHẤT)
        $query = "";
        $hashData = "";

        foreach ($inputData as $key => $value) {
            $encoded = urlencode($key) . "=" . urlencode($value);
            $query .= $encoded . "&";
            $hashData .= $encoded . "&";
        }

        $query = rtrim($query, '&');
        $hashData = rtrim($hashData, '&');

        // 🔥 HASH
        $vnp_SecureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $paymentUrl = $vnp_Url . "?" . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        // Lưu session
        Session::put('vnp_txn_ref', $vnp_TxnRef);
        Session::put('vnp_cart', $cart);
        Session::put('vnp_total', $total);

        Log::info("VNPAY URL", ['url' => $paymentUrl]);

        return redirect($paymentUrl);
    }

    public function vnpayReturn(Request $request)
    {
        $inputData = $request->except(['vnp_SecureHash','vnp_SecureHashType']);
        $vnp_SecureHash = $request->vnp_SecureHash;

        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                $hashData .= urlencode($key) . "=" . urlencode($value) . "&";
            }
        }

        $hashData = rtrim($hashData, '&');

        $secureHash = hash_hmac('sha512', $hashData, env('VNP_HASH_SECRET'));

        if ($secureHash === $vnp_SecureHash && $request->vnp_ResponseCode == '00') {

            $cart = Session::get('vnp_cart', []);
            $total = Session::get('vnp_total', 0);
            $shippingInfo = Session::get('shipping_info', []);

            if (empty($cart) || empty($shippingInfo)) {
                return view('user.payment_result', [
                    'status'=>'error',
                    'message'=>'Dữ liệu không hợp lệ'
                ]);
            }

            DB::beginTransaction();
            try {

                $ids = collect($cart)->keys()->map(fn($k)=>(int)$k);

                $products = Product::with('inventory')
                    ->whereIn('id', $ids)
                    ->get()
                    ->keyBy('id');

                // CHECK STOCK
                foreach ($cart as $pid => $item) {
                    $p = $products->get((int)$pid);
                    if (!$p || $item['quantity'] > ($p->inventory->stock ?? 0)) {
                        throw new \Exception('Sản phẩm hết hàng');
                    }
                }

                // CREATE ORDER
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'customer_name'=>$shippingInfo['customer_name'],
                    'phone'=>$shippingInfo['phone'],
                    'email'=>$shippingInfo['email'],
                    'address_line1'=>$shippingInfo['address_line1'],
                    'district'=>$shippingInfo['district'],
                    'province'=>$shippingInfo['province'],
                    'ward'=>$shippingInfo['ward'],
                    'status'=>'paid',
                    'payment_method'=>'vnpay',
                    'total'=>$total,
                    'paid_at'=>now(),
                ]);

                foreach ($cart as $pid => $item) {
                    $p = $products[$pid];

                    OrderItem::create([
                        'order_id'=>$order->id,
                        'product_id'=>$p->id,
                        'product_name'=>$p->name,
                        'quantity'=>$item['quantity'],
                        'price'=>$p->price,
                        'line_total'=>$p->price * $item['quantity']
                    ]);

                    // TRỪ KHO
                    Inventory::where('product_id',$p->id)
                        ->lockForUpdate()
                        ->decrement('stock', $item['quantity']);
                }

                DB::commit();

                Session::forget(['cart','vnp_cart','vnp_total','vnp_txn_ref','shipping_info']);

                return view('user.payment_result', [
                    'status'=>'success',
                    'message'=>'Thanh toán thành công'
                ]);

            } catch (\Throwable $e) {
                DB::rollBack();

                return view('user.payment_result', [
                    'status'=>'error',
                    'message'=>$e->getMessage()
                ]);
            }
        }

        return view('user.payment_result', [
            'status'=>'error',
            'message'=>'Thanh toán thất bại'
        ]);
    }

    public function ipn(Request $request)
    {
        return response()->json([
            'RspCode' => '00',
            'Message' => 'Confirm Success'
        ]);
    }
}