<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class OrderPlaceController extends Controller
{
    public function store(Request $req)
    {
        $req->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'province' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'ward' => 'required|string|max:100',

            'payment_method' => ['required', Rule::in(['deposit','full'])],
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng trống');
        }

        $ids = collect($cart)->keys()->map(fn($k)=>(int)$k)->values();

        $products = Product::with('inventory:id,product_id,stock')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $subtotal = 0;

        foreach ($cart as $pid => $item) {
            $p = $products->get((int)$pid);
            if (!$p) return back()->with('error', 'Sản phẩm không tồn tại');

            $stock = $p->inventory->stock ?? 0;
            if ($item['quantity'] > $stock) {
                return back()->with('error', "Không đủ hàng {$p->name}");
            }

            $price = $p->sale_price ?? $p->price;
            $subtotal += $price * $item['quantity'];
        }

        DB::beginTransaction();
        try {

            $deposit = $subtotal * 0.3;
            $remaining = $subtotal - $deposit;

            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $req->customer_name,
                'phone' => $req->phone,
                'email' => $req->email,
                'address_line1' => $req->address_line1,
                'address_line2' => $req->address_line2,
                'province' => $req->province,
                'district' => $req->district,
                'ward' => $req->ward,

                'status' => 'pending',
                'payment_method' => $req->payment_method,

                'subtotal' => $subtotal,
                'discount' => 0,
                'delivery_fee' => 0,
                'deposit_amount' => $deposit,
                'remaining_amount' => $remaining,
                'total' => $subtotal,
            ]);

            foreach ($cart as $pid => $item) {
                $p = $products->get((int)$pid);
                $price = $p->sale_price ?? $p->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'product_name' => $p->name,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                    'line_total' => $price * $item['quantity'],
                ]);

                Inventory::where('product_id', $p->id)
                    ->decrement('stock', $item['quantity']);
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

        Session::forget('cart');

        return redirect()->route('user.orders')
            ->with('success', 'Đặt hàng thành công!');
    }
}