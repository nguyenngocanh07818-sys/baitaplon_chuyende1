<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Kiểm tra đơn hàng thuộc user và đã hoàn thành
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', Auth::id())
            ->where('status', 'completed')
            ->firstOrFail();

        // Kiểm tra sản phẩm có trong đơn hàng
        $orderItem = OrderItem::where('order_id', $data['order_id'])
            ->where('product_id', $data['product_id'])
            ->firstOrFail();

        // Kiểm tra đã đánh giá chưa
        $existingReview = Review::where('user_id', Auth::id())
            ->where('order_id', $data['order_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này.');
        }

        // Lưu đánh giá
        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
        ]);

        return redirect()->back()->with('success', 'Đánh giá đã được gửi thành công!');
    }
}