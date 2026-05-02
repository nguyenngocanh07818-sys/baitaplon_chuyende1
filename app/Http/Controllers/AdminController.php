<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Tổng số đơn hàng
        $totalOrders = Order::where('status', 'completed')->count();

        // Doanh thu theo ngày (7 ngày gần nhất)
        $revenueByDate = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Doanh thu theo danh mục
        $categoryRevenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'products.category_id',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('SUM(order_items.quantity) as total_qty')
            )
            ->where('orders.status', 'completed')
            ->groupBy('products.category_id')
            ->get()
            ->map(function ($item) {
                $item->category_name = Category::find($item->category_id)->name ?? 'Unknown';
                return $item;
            });

        // Doanh thu theo tháng (12 tháng gần nhất)
        $revenueByMonth = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        // Doanh thu theo năm
        $revenueByYear = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->where('status', 'completed')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'revenueByDate',
            'categoryRevenue',
            'revenueByMonth',
            'revenueByYear'
        ));
    }
}