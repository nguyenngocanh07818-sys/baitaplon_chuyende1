@extends('layouts.user')

@section('title','Giỏ hàng')

@push('styles')
<style>
.hk-btn{background:#ff7eb8;color:#fff;padding:8px 12px;border-radius:8px}
.hk-btn:hover{background:#ff5fa7}
.hk-ghost{border:1px solid #ffc2dd;background:#fff0f6;padding:8px 12px;border-radius:8px}
.hk-danger{background:#ff6b8a;color:#fff;padding:6px 10px;border-radius:6px}
.hk-toast{position:fixed;top:80px;right:20px;background:#333;color:#fff;padding:10px 15px;border-radius:6px;opacity:0;transition:.3s}
.hk-toast.show{opacity:1}
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto p-4">

<h1 class="text-2xl font-bold mb-6">🛒 Giỏ hàng</h1>

@if(empty($cart))
<div class="bg-white p-6 rounded shadow text-center">
    Giỏ hàng trống
</div>
@else

@php $total = 0; @endphp

@foreach($cart as $id => $item)
@php
    $line = $item['price'] * $item['quantity'];
    $total += $line;
@endphp

<div class="bg-white p-4 mb-4 rounded shadow flex gap-4 items-center">

<img src="{{ $item['thumbnail'] }}"
     onerror="this.src='https://via.placeholder.com/150'"
     class="w-24 h-24 object-cover rounded">

<div class="flex-1">
    <div class="font-bold">{{ $item['name'] }}</div>
    <div class="text-red-500">{{ number_format($item['price']) }}₫</div>
</div>

<form action="{{ route('cart.update',$id) }}" method="POST">
@csrf @method('PATCH')
<input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border">
<button class="hk-ghost">Cập nhật</button>
</form>

<div>{{ number_format($line) }}₫</div>

<form action="{{ route('cart.remove',$id) }}" method="POST">
@csrf @method('DELETE')
<button class="hk-danger">Xoá</button>
</form>

</div>
@endforeach

<div class="bg-white p-4 rounded shadow flex justify-between mb-6">
<b>Tổng:</b>
<b class="text-red-600">{{ number_format($total) }}₫</b>
</div>

{{-- FORM THANH TOÁN --}}
<div class="bg-white p-6 rounded shadow">

<form action="{{ route('vnpay.create') }}" method="POST" id="checkoutForm">
@csrf

<input type="text" name="customer_name" placeholder="Tên *" required class="w-full mb-2">
<input type="text" name="phone" placeholder="SĐT *" required class="w-full mb-2">
<input type="email" name="email" placeholder="Email *" required class="w-full mb-2">

<input type="text" name="address_line1" placeholder="Địa chỉ *" required class="w-full mb-2">

<div class="grid grid-cols-3 gap-2 mb-2">
<select name="province" id="province" required></select>
<select name="district" id="district" required disabled></select>
<select name="ward" id="ward" required disabled></select>
</div>

{{-- FIX BOOLEAN --}}
<input type="hidden" name="age_confirmed" value="0">
<label>
    <input type="checkbox" name="age_confirmed" value="1" checked>
    Xác nhận
</label>

<br><br>

<button class="hk-btn">Thanh toán VNPay</button>

</form>
</div>

@endif
</div>

<div id="toast" class="hk-toast"></div>

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
    try{
        let res = await fetch('/api/provinces');
        let data = await res.json();
        let el = document.getElementById('province');
        el.innerHTML = '<option value="">Tỉnh</option>';
        data.forEach(p=>{
            let opt = document.createElement('option');
            opt.value = p.name;
            opt.dataset.code = p.code;
            opt.textContent = p.name;
            el.appendChild(opt);
        });
    }catch(e){ console.log(e); }
}

async function loadDistricts(code){
    let el = document.getElementById('district');
    el.innerHTML = '<option>Quận</option>';
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
    el.innerHTML = '<option>Xã</option>';
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
const province = document.getElementById('province');
const district = document.getElementById('district');

if(province){
province.addEventListener('change',()=>{
    let code = province.selectedOptions[0].dataset.code;
    if(code) loadDistricts(code);
});
}

if(district){
district.addEventListener('change',()=>{
    let code = district.selectedOptions[0].dataset.code;
    if(code) loadWards(code);
});
}

// ===== VALIDATE =====
const form = document.getElementById('checkoutForm');
if(form){
form.addEventListener('submit',(e)=>{
    let required = ['customer_name','phone','email','address_line1','province','district','ward'];
    for(let f of required){
        let el = document.querySelector(`[name="${f}"]`);
        if(!el || !el.value){
            showToast('Thiếu: '+f);
            e.preventDefault();
            return;
        }
    }
});
}

// INIT
loadProvinces();
</script>
@endsection