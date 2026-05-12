@extends('layouts.user')

@section('title','Welcome')

@section('content')
<div class="card">
    <h1>Chào mừng, {{ auth()->user()->name }} 🎉</h1>
    <p>Bạn đã đăng nhập thành công. Đây là trang dành cho người dùng.</p>
</div>
@endsection
