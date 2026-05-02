@extends('layouts.user')

@section('title','Giỏ hàng')

@push('styles')
<style>
.hk-btn{background:#ff7eb8;color:#fff;padding:10px 16px;border-radius:10px;font-weight:600}
.hk-btn:hover{background:#ff5fa7}

.hk-ghost{border:1px solid #ffc2dd;background:#fff0f6;padding:6px 10px;border-radius:8px}
.hk-danger{background:#ff6b8a;color:#fff;padding:6px 10px;border-radius:8px}

.hk-card{background:#fff;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.05)}

.hk-input{width:100%;padding:10px;border:1px solid #eee;border-radius:8px}
.hk-input:focus{outline:none;border-color:#ff7eb8}

.hk-toast{position:fixed;top:80px;right:20px;background:#333;color:#fff;padding:10px 15px;border-radius:6px;opacity:0;transition:.3s}
.hk-toast.show{opacity:1}
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto p-4">

<h1 class="text-2xl font-bold mb-6">🛒 Giỏ hàng của bạn</h1>

@if(empty($cart))

<div class="hk-card p-10 text-center">
    <p class="text-gray-500">Giỏ hàng đang trống</p>
</div>

@else

<div class="grid md:grid-cols-3 gap-6">

{{-- ===== DANH SÁCH SẢN PHẨM ===== --}}
<div class="md:col-span-2 space-y-4">

@php $total = 0; @endphp

@foreach($cart as $id => $item)
@php
    $line = $item['price'] * $item['quantity'];
    $total += $line;
@endphp

<div class="hk-card p-4 flex gap-4 items-center">

<img src="{{ $item['thumbnail'] }}"
     onerror="this.src='https://via.placeholder.com/150'"
     class="w-20 h-20 object-cover rounded-lg">

<div class="flex-1">
    <div class="font-semibold">{{ $item['name'] }}</div>
    <div class="text-pink-500 font-bold">{{ number_format($item['price']) }}₫</div>
</div>

<form action="{{ route('cart.update',$id) }}" method="POST" class="flex items-center gap-2">
@csrf @method('PATCH')
<input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
       class="w-16 border rounded text-center">
<button class="hk-ghost">OK</button>
</form>

<div class="font-semibold w-24 text-right">
    {{ number_format($line) }}₫
</div>

<form action="{{ route('cart.remove',$id) }}" method="POST">
@csrf @method('DELETE')
<button class="hk-danger">X</button>
</form>

</div>
@endforeach

</div>

{{-- ===== THANH TOÁN ===== --}}
<div class="hk-card p-6 h-fit">

<h2 class="font-bold text-lg mb-4">Thông tin đặt hàng</h2>

<form action="{{ route('order.store') }}" method="POST" id="checkoutForm">
@csrf

<input type="text" name="customer_name" placeholder="Họ tên *" class="hk-input mb-2">
<input type="text" name="phone" placeholder="SĐT *" class="hk-input mb-2">
<input type="email" name="email" placeholder="Email *" class="hk-input mb-2">

<input type="text" name="address_line1" placeholder="Địa chỉ cụ thể *" class="hk-input mb-2">

<div class="grid grid-cols-1 gap-2 mb-2">
<select name="province" id="province" class="hk-input"></select>
<select name="district" id="district" class="hk-input" disabled></select>
<select name="ward" id="ward" class="hk-input" disabled></select>
</div>

<div class="flex justify-between border-t pt-3 mt-3">
    <span>Tổng tiền:</span>
    <span class="text-pink-600 font-bold text-lg">{{ number_format($total) }}₫</span>
</div>

<button type="submit" class="hk-btn w-full mt-4">
    Đặt hàng
</button>

</form>

</div>

</div>

@endif
</div>

{{-- TOAST --}}
<div id="toast" class="hk-toast"></div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded',()=>{
    showToast("{{ session('success') }}");
});
</script>
@endif

<script>
// ===== TOAST =====
function showToast(msg){
    let t=document.getElementById('toast');
    t.innerText=msg;
    t.classList.add('show');
    setTimeout(()=>t.classList.remove('show'),3000);
}

// ===== API ĐỊA CHỈ =====
async function loadProvinces(){
    let res = await fetch('/api/provinces');
    let data = await res.json();
    let el = document.getElementById('province');
    el.innerHTML = '<option value="">Tỉnh / Thành</option>';
    data.forEach(p=>{
        let opt = document.createElement('option');
        opt.value = p.name;
        opt.dataset.code = p.code;
        opt.textContent = p.name;
        el.appendChild(opt);
    });
}

async function loadDistricts(code){
    let el = document.getElementById('district');
    el.innerHTML = '<option>Quận / Huyện</option>';
    el.disabled=true;

    let res = await fetch('/api/districts/'+code);
    let data = await res.json();

    data.forEach(d=>{
        let opt = document.createElement('option');
        opt.value=d.name;
        opt.dataset.code=d.code;
        opt.textContent=d.name;
        el.appendChild(opt);
    });

    el.disabled=false;
}

async function loadWards(code){
    let el = document.getElementById('ward');
    el.innerHTML = '<option>Xã / Phường</option>';
    el.disabled=true;

    let res = await fetch('/api/wards/'+code);
    let data = await res.json();

    data.forEach(w=>{
        let opt = document.createElement('option');
        opt.value=w.name;
        opt.textContent=w.name;
        el.appendChild(opt);
    });

    el.disabled=false;
}

// ===== EVENT =====
document.getElementById('province')?.addEventListener('change',function(){
    let code = this.selectedOptions[0].dataset.code;
    if(code) loadDistricts(code);
});

document.getElementById('district')?.addEventListener('change',function(){
    let code = this.selectedOptions[0].dataset.code;
    if(code) loadWards(code);
});

// ===== VALIDATE + CONFIRM =====
document.getElementById('checkoutForm')?.addEventListener('submit',(e)=>{

    let required = ['customer_name','phone','email','address_line1','province','district','ward'];

    for(let f of required){
        let el = document.querySelector(`[name="${f}"]`);
        if(!el || !el.value){
            showToast('Vui lòng nhập đầy đủ thông tin');
            e.preventDefault();
            return;
        }
    }

    if(!confirm("Xác nhận đặt hàng?")){
        e.preventDefault();
    }
});

// INIT
loadProvinces();
</script>

@endsection