@extends('layouts.user')

@section('title', 'Kết quả thanh toán')

@push('styles')
<style>
.hk-card { box-shadow: 0 10px 20px rgba(255,126,184,.15); }
.hk-btn { @apply px-3 py-2 rounded-md text-white; background: #ff7eb8; }
.hk-btn:hover { background: #ff5fa7; }
.hk-toast { position: fixed; right: 1rem; top: 5rem; z-index: 50; background: #8b1c49; color: #fff; padding: .75rem 1rem; border-radius: .75rem; box-shadow: 0 10px 20px rgba(255,126,184,.25); }
.hk-toast.error { background: #ff6b8a; }
</style>
@endpush

@section('content')
<div class="bg-white rounded-lg p-6 hk-card text-center">
    <h1 class="text-2xl font-bold mb-4">
        {{ $status == 'success' ? '✅ Thanh toán thành công' : '❌ Thanh toán thất bại' }}
    </h1>
    <p class="text-lg mb-4">{{ $message }}</p>
    <p class="text-sm text-slate-600">Bạn sẽ được chuyển về trang chủ sau 5 giây...</p>
    <a href="{{ route('user.welcome') }}" class="mt-4 inline-block hk-btn">Về trang chủ ngay</a>
</div>

<script>
    setTimeout(() => {
        window.location.href = "{{ route('user.welcome') }}";
    }, 5000);
</script>
@endsection