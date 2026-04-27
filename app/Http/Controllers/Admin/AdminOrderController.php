<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminOrderController extends Controller
{
    public function list(Request $req)
    {
        $perPage = max(1, min((int)$req->integer('per_page', 12), 100));
        $q = (string)$req->get('q', '');

        $query = Order::query()
            ->with(['user:id,name,email'])
            ->when($q, function ($qr) use ($q) {
                $qr->where('id', $q)
                   ->orWhere('customer_name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%");
            })
            ->orderByDesc('id');

        $page = $query->paginate($perPage);

        return response()->json([
            'ok'   => true,
            'data' => $page->items(),
            'meta' => [
                'current_page' => $page->currentPage(),
                'last_page'    => $page->lastPage(),
                'per_page'     => $page->perPage(),
                'total'        => $page->total(),
            ]
        ]);
    }

    public function update(Request $req, Order $order)
    {
        $validStatuses = [
            'pending',
            'processing',
            'paid',
            'shipped',
            'completed',
            'cancelled',
            'refunded'
        ];

        $data = $req->validate([
            'status' => ['required', Rule::in($validStatuses)],
            'notes'  => ['nullable','string','max:500'],
        ]);

        $order->update($data);

        return response()->json([
            'ok' => true,
            'message' => 'Cập nhật trạng thái thành công',
            'data' => $order
        ]);
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Đã xoá đơn hàng.'
        ]);
    }
}