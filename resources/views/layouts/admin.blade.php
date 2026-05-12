<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin | @yield('title','Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial;}
        .wrap{display:flex;min-height:100vh;}
        .sidebar{width:240px;background:#0f172a;color:#fff;display:flex;flex-direction:column;}
        .sidebar a{color:#cbd5e1;text-decoration:none;padding:12px 16px;display:block}
        .sidebar a.active,.sidebar a:hover{background:#1e293b;color:#fff}
        .content{flex:1;background:#f8fafc}
        .topbar{background:#fff;border-bottom:1px solid #e2e8f0;padding:12px 16px;display:flex;justify-content:space-between;align-items:center}
        .main{padding:24px}
        .btn{padding:8px 12px;border:1px solid #e2e8f0;background:#fff;border-radius:6px;cursor:pointer}
        .btn:hover{background:#f1f5f9}
        .user{color:#475569}
        .toast{position:fixed;right:16px;top:72px;background:#0ea5e9;color:#fff;padding:10px 12px;border-radius:8px;box-shadow:0 8px 24px rgba(2,6,23,.25);opacity:0;transform:translateY(-6px);transition:.25s}
        .toast.show{opacity:1;transform:none}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-top:1px solid #e2e8f0;text-align:left}
        thead th{background:#f1f5f9}
        input,select{padding:8px 10px;border:1px solid #e2e8f0;border-radius:6px}
        .danger{background:#fee2e2;border:1px solid #fecaca}
    </style>
    @stack('styles')
</head>
<body>
<div class="wrap">
    <aside class="sidebar">
        <div style="padding:16px 16px 8px;font-weight:700;">ADMIN PANEL</div>
        <a href="{{ route('admin.dashboard') }}"         class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Bảng điều khiển</a>
        <a href="{{ route('admin.products.index') }}"    class="{{ request()->is('admin/products*') ? 'active' : '' }}">Sản phẩm</a>
        <a href="{{ route('admin.categories.index') }}"  class="{{ request()->is('admin/categories*') ? 'active' : '' }}">Danh mục</a>
        <a href="{{ route('admin.brands.index') }}"      class="{{ request()->is('admin/brands*') ? 'active' : '' }}">Thương hiệu</a>
        <a href="{{ route('admin.orders.index') }}"      class="{{ request()->is('admin/orders*') ? 'active' : '' }}">Đơn hàng</a>
        <a href="{{ route('admin.inventories.index') }}" class="{{ request()->is('admin/inventories*') ? 'active' : '' }}">Tồn kho</a>
        <a href="{{ route('admin.images.index') }}"      class="{{ request()->is('admin/images*') ? 'active' : '' }}">Ảnh sản phẩm</a>
        <a href="{{ route('admin.users.index') }}"       class="{{ request()->is('admin/users*') ? 'active' : '' }}">Người dùng</a>
        <a href="{{ route('admin.reports.index') }}"     class="{{ request()->is('admin/reports*') ? 'active' : '' }}">Báo cáo</a>

        <form method="POST" action="{{ route('logout') }}" style="margin-top:auto;padding:16px">
            @csrf
            <button type="submit" class="btn" style="width:100%">Đăng xuất</button>
        </form>
    </aside>

    <div class="content">
        <div class="topbar">
            <div>@yield('breadcrumb')</div>
            <div class="user">@auth Xin chào, <strong>{{ auth()->user()->name }}</strong> @endauth</div>
        </div>
        <main class="main">
            @if (session('success'))
                <div class="toast show" id="flash-toast">{{ session('success') }}</div>
                <script>setTimeout(()=>document.getElementById('flash-toast')?.classList.remove('show'),3000)</script>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<div class="toast" id="toast"></div>
<script>
function showToast(msg, ok=true){
    const t=document.getElementById('toast');
    t.textContent=msg; t.style.background= ok ? '#16a34a' : '#ef4444';
    t.classList.add('show'); setTimeout(()=>t.classList.remove('show'),3000);
}
</script>
@stack('scripts')
</body>
</html>
