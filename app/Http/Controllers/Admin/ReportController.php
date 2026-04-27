<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // doanh thu chỉ tính đơn đã thanh toán (paid) hoặc đã giao (completed)
        $paidStatuses = ['paid', 'completed'];
        $shippedDone  = ['completed'];

        // 1) Doanh thu theo danh mục
        $categoryRevenue = DB::table('order_items')
            ->join('products','order_items.product_id','=','products.id')
            ->join('orders','order_items.order_id','=','orders.id')
            ->select('products.category_id',
                DB::raw('SUM(order_items.price * order_items.quantity) AS total_revenue'),
                DB::raw('SUM(order_items.quantity) AS total_qty')
            )
            ->whereIn('orders.status', $paidStatuses) // hoặc ->whereIn('orders.shipping_status', $shippedDone)
            ->groupBy('products.category_id')
            ->orderByDesc('total_revenue')
            ->get();

        // 2) Tổng số đơn hàng
        $totalOrders = Order::count();

        // 3) Doanh thu theo ngày (7 ngày gần nhất)
        $revenueByDate = Order::query()
            ->whereIn('status', $paidStatuses)
            ->selectRaw('DATE(created_at) AS date, SUM(total) AS total_revenue, COUNT(*) AS order_count')
            ->groupBy('date')
            ->orderBy('date','desc')->limit(14)->get()->reverse()->values();

        // 4) Doanh thu theo tháng (12 tháng)
        $revenueByMonth = Order::query()
            ->whereIn('status',$paidStatuses)
            ->selectRaw('DATE_FORMAT(created_at,"%Y-%m") AS month, SUM(total) AS total_revenue, COUNT(*) AS order_count')
            ->groupBy('month')->orderBy('month')->limit(12)->get();

        // 5) Doanh thu theo năm
        $revenueByYear = Order::query()
            ->whereIn('status',$paidStatuses)
            ->selectRaw('YEAR(created_at) AS year, SUM(total) AS total_revenue, COUNT(*) AS order_count')
            ->groupBy('year')->orderBy('year')->get();

        return view('admin.reports.index', compact(
            'categoryRevenue','totalOrders','revenueByDate','revenueByMonth','revenueByYear'
        ));
    }
}
