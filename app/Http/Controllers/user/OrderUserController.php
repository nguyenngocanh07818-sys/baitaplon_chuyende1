<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderUserController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng của người dùng
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest('created_at')
            ->get();

        return view('user.orders', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        // Kiểm tra quyền sở hữu
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('user.orders')->with('error', 'Bạn không có quyền xem đơn hàng này.');
        }

        $items = OrderItem::where('order_id', $order->id)->get();

        return view('user.order_details', compact('order', 'items'));
    }
}