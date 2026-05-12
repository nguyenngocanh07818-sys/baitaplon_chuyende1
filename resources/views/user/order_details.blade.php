@extends('layouts.user')

@section('title', 'Chi tiết đơn hàng')

@push('styles')
<style>
.hk-card { box-shadow: 0 10px 20px rgba(255,126,184,.15); }
.hk-btn { @apply px-3 py-2 rounded-md text-white; background: #ff7eb8; }
.hk-btn:hover { background: #ff5fa7; }
.hk-toast { position: fixed; right: 1rem; top: 5rem; z-index: 50; background: #8b1c49; color: #fff; padding: .75rem 1rem; border-radius: .75rem; box-shadow: 0 10px 20px rgba(255,126,184,.25); opacity: 0; transform: translateY(-8px); transition: .25s; }
.hk-toast.show { opacity: 1; transform: none; }
.hk-toast.error { background: #ff6b8a; }
select, input, textarea { @apply border border-pink-200 rounded-md px-2 py-1; }
.rating-star { cursor: pointer; color: #d1d5db; }
.rating-star.selected { color: #f59e0b; }
</style>
@endpush

@section('content')
<div class="bg-white rounded-lg p-6 hk-card">
    <h1 class="text-2xl font-bold mb-4">📋 Chi tiết đơn hàng #{{ $order->id }}</h1>

    @if(session('success'))
        <div id="toast" class="hk-toast show"><span>{{ session('success') }}</span></div>
    @elseif(session('error'))
        <div id="toast" class="hk-toast show error"><span>{{ session('error') }}</span></div>
    @else
        <div id="toast" class="hk-toast"><span id="toastText"></span></div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h2 class="text-lg font-semibold mb-2">Thông tin đơn hàng</h2>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Trạng thái:</strong>
                <span class="px-2 py-1 text-sm rounded bg-pink-100 text-pink-700">
                    {{ match($order->status) {
                        'pending' => 'Chờ xử lý',
                        'processing' => 'Đang xử lý',
                        'paid' => 'Đã thanh toán (chờ giao)',
                        'shipped' => 'Đã giao hàng',
                        'completed' => 'Hoàn thành',
                        'cancelled' => 'Đã hủy',
                        'refunded' => 'Đã hoàn tiền',
                        default => $order->status,
                    } }}
                </span>
            </p>
            <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method == 'online' ? 'Thanh toán trực tuyến (VNPay)' : 'Thanh toán khi nhận hàng (COD)' }}</p>
            @if($order->paid_at)
                <p><strong>Thời gian thanh toán:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}</p>
            @endif
            @if($order->shipped_at)
                <p><strong>Thời gian giao hàng:</strong> {{ $order->shipped_at->format('d/m/Y H:i') }}</p>
            @endif
            @if($order->tracking_number)
                <p><strong>Mã vận đơn:</strong> {{ $order->tracking_number }}</p>
            @endif
            @if($order->notes)
                <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
            @endif
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2">Thông tin giao hàng</h2>
            <p><strong>Họ tên:</strong> {{ $order->customer_name ?? 'Không có' }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? 'Không có' }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone ?? 'Không có' }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address_line1 ?? 'Không có' }} {{ $order->address_line2 ? ', ' . $order->address_line2 : '' }}</p>
            <p><strong>Xã/Phường:</strong> {{ $order->ward ?? 'Không có' }}</p>
            <p><strong>Quận/Huyện:</strong> {{ $order->district ?? 'Không có' }}</p>
            <p><strong>Tỉnh/Thành phố:</strong> {{ $order->province ?? 'Không có' }}</p>
            <p><strong>Mã bưu điện:</strong> {{ $order->postal_code ?? 'Không có' }}</p>
            <p><strong>Xác nhận tuổi:</strong> {{ $order->age_confirmed ? 'Có' : 'Không' }}</p>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Thông tin tài chính</h2>
        <p><strong>Tổng phụ (chưa phí):</strong> {{ number_format($order->subtotal, 0, ',', '.') }}₫</p>
        <p><strong>Giảm giá:</strong> {{ number_format($order->discount, 0, ',', '.') }}₫</p>
        <p><strong>Phí vận chuyển:</strong> {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
        <p><strong>Thuế:</strong> {{ number_format($order->tax, 0, ',', '.') }}₫</p>
        <p><strong>Tổng cộng:</strong> {{ number_format($order->total, 0, ',', '.') }}₫</p>
    </div>

    <h2 class="text-lg font-semibold mb-2">Sản phẩm trong đơn hàng</h2>
    <div class="overflow-x-auto mb-6">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-pink-50 text-pink-900">
                    <th class="p-2 text-left">Sản phẩm</th>
                    <th class="p-2 text-center">Số lượng</th>
                    <th class="p-2 text-center">Giá</th>
                    <th class="p-2 text-center">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr class="border-t">
                        <td class="p-2">{{ $item->product_name }}</td>
                        <td class="p-2 text-center">{{ $item->quantity }}</td>
                        <td class="p-2 text-center">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td class="p-2 text-center">{{ number_format($item->line_total, 0, ',', '.') }}₫</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($order->status == 'completed')
        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">Đánh giá sản phẩm</h2>
            @foreach($items as $item)
                @php
                    $existingReview = $item->reviews()->where('user_id', auth()->id())->first();
                @endphp
                <div class="border-t pt-4 mt-4">
                    <h3 class="font-medium">{{ $item->product_name }}</h3>
                    @if($existingReview)
                        <p class="text-sm text-gray-600">Bạn đã đánh giá: {{ $existingReview->rating }} sao - {{ $existingReview->comment }}</p>
                    @else
                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                            <div>
                                <label class="block text-sm font-medium">Đánh giá (1-5 sao):</label>
                                <div class="flex gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="rating-star" data-value="{{ $i }}">★</span>
                                    @endfor
                                    <input type="hidden" name="rating" id="rating-{{ $item->product_id }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Nhận xét:</label>
                                <textarea name="comment" rows="3" class="w-full" placeholder="Nhập nhận xét của bạn"></textarea>
                            </div>
                            <button type="submit" class="hk-btn">Gửi đánh giá</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <a href="{{ route('user.orders') }}" class="mt-4 inline-block hk-btn">Quay lại lịch sử đơn hàng</a>
</div>

<script>
    // Toast auto hide
    setTimeout(() => {
        const toast = document.getElementById('toast');
        if (toast) toast.classList.remove('show');
    }, 5000);

    // Rating star logic
    document.querySelectorAll('.rating-star').forEach(star => {
        star.addEventListener('click', () => {
            const rating = star.dataset.value;
            const parent = star.parentElement;
            const productId = parent.querySelector('input[name="rating"]').id.split('-')[1];
            parent.querySelectorAll('.rating-star').forEach(s => {
                s.classList.toggle('selected', s.dataset.value <= rating);
            });
            document.getElementById(`rating-${productId}`).value = rating;
        });
    });
</script>
@endsection