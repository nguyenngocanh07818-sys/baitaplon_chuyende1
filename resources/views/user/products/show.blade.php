@extends('layouts.user')

@section('title', $product->name)

@push('styles')
<style>
.product-image { 
  width: 100%; max-width: 420px; height: 420px;
  object-fit: cover; border-radius: .75rem;
  border: 1px solid #e5e7eb; margin: auto; display: block;
}
.info-grid {
  display: grid; grid-template-columns: 160px 1fr;
  gap: .5rem 1rem; font-size: .95rem;
}
.info-grid dt { font-weight: 600; color: #111827; }
.info-grid dd { color: #374151; }
.review-item { border-bottom: 1px solid #eee; padding: 1rem 0; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto p-4">

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    {{-- HÌNH ẢNH --}}
    <div class="bg-white rounded-xl shadow p-4">
      <img src="{{ $product->thumbnail }}" 
           class="w-full h-[420px] object-cover rounded-lg border">

      @if(($product->inventory->stock ?? 0) <= 0)
        <div class="mt-3 text-red-600 font-semibold text-center">
          ❌ Hết hàng
        </div>
      @endif
    </div>

    {{-- THÔNG TIN --}}
    <div class="bg-white rounded-xl shadow p-6">

      <h1 class="text-2xl font-bold mb-2">
        {{ $product->name }}
      </h1>

      <p class="text-sm text-gray-500 mb-2">
        {{ $product->brand->name ?? 'Không rõ hãng' }} • {{ $product->category->name ?? '' }}
      </p>

      {{-- GIÁ --}}
      <div class="mb-4">
        <span class="text-3xl font-bold text-red-600">
          {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
        </span>

        @if($product->sale_price)
          <span class="line-through text-gray-400 ml-2">
            {{ number_format($product->price, 0, ',', '.') }}₫
          </span>

          <span class="ml-2 bg-red-100 text-red-600 px-2 py-1 text-xs rounded">
            Giảm giá
          </span>
        @endif
      </div>

      {{-- ĐÁNH GIÁ --}}
      <div class="flex items-center gap-2 mb-4">
        @for($i=1; $i<=5; $i++)
          <span class="{{ $i <= round($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
        @endfor
        <span class="text-sm text-gray-500">
          ({{ $product->reviews->count() }} đánh giá)
        </span>
      </div>

      {{-- KHO --}}
      <div class="mb-4">
        <span class="px-3 py-1 rounded bg-gray-100 text-sm">
          Số lượng còn: {{ $product->inventory->stock ?? 0 }}
        </span>
      </div>

      {{-- THÔNG SỐ --}}
      <div class="grid grid-cols-2 gap-2 text-sm mb-4">
        <div>🔖 Mã SP: {{ $product->sku }}</div>
        <div>⚙ Dung tích: {{ $product->engine_capacity ?? '--' }} cc</div>

        <div>
          ⛽ Nhiên liệu:
          {{
            $product->fuel_type == 'gasoline' ? 'Xăng' :
            ($product->fuel_type == 'electric' ? 'Điện' : '--')
          }}
        </div>

        <div>
          🔄 Hộp số:
          {{
            $product->transmission == 'manual' ? 'Số tay' :
            ($product->transmission == 'automatic' ? 'Tự động' : '--')
          }}
        </div>
      </div>

      {{-- MUA HÀNG --}}
      <form id="addCartForm" class="flex items-center gap-3 mt-4">
        @csrf

        <input type="number" name="quantity"
               min="1"
               max="{{ $product->inventory->stock ?? 1 }}"
               value="1"
               class="w-20 border rounded px-2 py-1">

        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded font-semibold"
                {{ ($product->inventory->stock ?? 0) <= 0 ? 'disabled' : '' }}>
          🛒 Thêm vào giỏ
        </button>
      </form>

      {{-- MÔ TẢ --}}
      <div class="mt-6 text-sm text-gray-700 leading-relaxed">
        {!! nl2br(e($product->description)) !!}
      </div>

    </div>
  </div>

  {{-- ĐÁNH GIÁ --}}
  <div class="bg-white rounded-xl shadow p-6 mt-8">
    <h2 class="text-lg font-semibold mb-4">Đánh giá sản phẩm</h2>

    @forelse($product->reviews as $review)
      <div class="border-b py-3">
        <div class="flex items-center gap-2">
          @for($i=1; $i<=5; $i++)
            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
          @endfor

          <span class="text-xs text-gray-500">
            {{ $review->user->name ?? 'Khách' }} • {{ $review->created_at->format('d/m/Y') }}
          </span>
        </div>

        <p class="text-sm mt-1">
          {{ $review->comment ?: 'Không có nhận xét' }}
        </p>
      </div>
    @empty
      <p class="text-sm text-gray-500">Chưa có đánh giá nào</p>
    @endforelse
  </div>

</div>
@endsection
@push('scripts')
<script>
const token = "{{ csrf_token() }}";

document.getElementById('addCartForm')?.addEventListener('submit', async function(e){
    e.preventDefault();

    let qty = this.querySelector('[name="quantity"]').value;

    let res = await fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            product_id: {{ $product->id }},
            quantity: qty
        })
    });

    let j = await res.json();

    if(j.ok){
        alert('Đã thêm vào giỏ');
    }else{
        alert(j.message || 'Lỗi');
    }
});
</script>
@endpush