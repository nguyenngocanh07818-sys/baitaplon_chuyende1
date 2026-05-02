@extends('layouts.user')

@section('title', 'Lịch sử đơn hàng')

@push('styles')
<style>
.hk-card { box-shadow: 0 10px 20px rgba(255,126,184,.15); }
.hk-btn { @apply px-3 py-2 rounded-md text-white; background: #ff7eb8;border-radius: 10px;  padding: 0.4rem 0.5rem;}
.hk-btn:hover { background: #ff5fa7; }
</style>
@endpush

@section('content')
<div class="bg-white rounded-lg p-6 hk-card">
    <h1 class="text-2xl font-bold mb-4">📜 Lịch sử đơn hàng</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-700">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-700">{{ session('error') }}</div>
    @endif

    @if(empty($orders) || count($orders) === 0)
        <p class="text-slate-600">Bạn chưa có đơn hàng nào.</p>
        <a href="{{ route('user.home') }}" class="mt-3 inline-block hk-btn">Tiếp tục mua sắm</a>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-pink-50 text-pink-900">
                        <th class="p-2 text-left">Mã đơn</th>
                        <th class="p-2 text-center">Ngày đặt</th>
                        <th class="p-2 text-center">Trạng thái</th>
                        <th class="p-2 text-center">Tổng tiền</th>
                        <th class="p-2 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-t">
                            <td class="p-2">#{{ $order->id }}</td>
                            <td class="p-2 text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2 text-center">
                                <span class="px-2 py-1 text-sm rounded bg-pink-100 text-pink-700">
                                    {{ match($order->status) {
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'paid' => 'Đã thanh toán',
                                        'shipped' => 'Đã giao hàng',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                        'refunded' => 'Đã hoàn tiền',
                                        default => $order->status,
                                    } }}
                                </span>
                            </td>
                            <td class="p-2 text-center">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                            <td class="p-2 text-right">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="text-pink-700 hover:underline">Chi tiết</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <a href="{{ route('user.home') }}" class="mt-4 inline-block hk-btn">Tiếp tục mua sắm</a>
    @endif
</div>
@endsection