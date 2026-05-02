<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem; // ✅ thêm
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    // ================= GIỮ NGUYÊN =================
    public function list(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $perPage = (int)($request->get('per_page', 10));

        $orders = Order::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('id', $q)
                   ->orWhere('customer_name', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('province', 'like', "%$q%")
                   ->orWhere('district', 'like', "%$q%")
                   ->orWhere('ward', 'like', "%$q%")
                   ->orWhere('status', 'like', "%$q%");
            })
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ],
        ]);
    }

    // ================= SỬA STORE =================
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng trống');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'required|email',

            'address_line1' => 'required|string|max:255',
            'ward' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'required|string|max:100',

            // mặc định luôn full (bỏ VNPay)
            'payment_method' => ['nullable', Rule::in(['deposit', 'full'])],
        ]);

        // ===== TÍNH TIỀN =====
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $delivery_fee = 0;
        $discount = 0;
        $total = $subtotal + $delivery_fee - $discount;

        // ===== TẠO ORDER =====
        $order = Order::create([
            'user_id' => auth()->id(), 
            'customer_name' => $data['customer_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],

            'address_line1' => $data['address_line1'],
            'ward' => $data['ward'],
            'district' => $data['district'],
            'province' => $data['province'],

            'payment_method' => 'full', // ✅ luôn full
            'status' => 'pending',

            'subtotal' => $subtotal,
            'discount' => $discount,
            'delivery_fee' => $delivery_fee,
            'total' => $total,
        ]);

        // ===== LƯU ORDER ITEMS =====
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],

                'sku' => $item['sku'] ?? null,
                'color' => $item['color'] ?? null,
                'version' => $item['version'] ?? null,

                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['price'] * $item['quantity'],
            ]);
        }

        // ===== XOÁ GIỎ =====
        session()->forget('cart');

        return redirect()
            ->route('user.cart')
            ->with('success', 'Đặt hàng thành công!');
    }

    // ================= GIỮ NGUYÊN =================
    public function update(Request $request, Order $order)
    {
        $validStatuses = match ($order->status) {
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['deposit_paid', 'cancelled'],
            'deposit_paid' => ['delivered', 'cancelled'],
            'delivered' => ['completed'],
            'completed', 'cancelled' => [],
            default => [],
        };

        $data = $request->validate([
            'status' => ['sometimes', 'required', Rule::in($validStatuses)],
            'customer_name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'payment_method' => ['sometimes', Rule::in(['deposit','full'])],
            'subtotal' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'remaining_amount' => 'nullable|numeric|min:0',
            'total' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
        ]);

        $order->update($data);

        return response()->json([
            'ok' => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'data' => $order
        ]);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Đã xóa đơn hàng'
        ]);
    }
}