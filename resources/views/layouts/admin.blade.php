<!DOCTYPE html>
<html lang="vi" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>Admin | @yield('title','Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        .sidebar-link {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover {
            transform: translateX(4px);
        }
        
        .toast {
            animation: slideIn 0.3s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
    
    @stack('styles')
</head>
<body class="h-full bg-slate-50">
<div class="flex h-full">
    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-slate-900 flex flex-col z-50 shadow-xl">
        <!-- Logo/Brand -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-800">
            <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <i class="fas fa-cube text-white text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight">ADMIN</h1>
                <p class="text-xs text-slate-400">Management Panel</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Menu chính</p>
            
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-th-large w-5 text-center"></i>
                <span>Bảng điều khiển</span>
            </a>
            
            <a href="{{ route('admin.products.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/products*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-box w-5 text-center"></i>
                <span>Sản phẩm</span>
            </a>
            
            <a href="{{ route('admin.categories.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/categories*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-tags w-5 text-center"></i>
                <span>Danh mục</span>
            </a>
            
            <a href="{{ route('admin.brands.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/brands*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-trademark w-5 text-center"></i>
                <span>Thương hiệu</span>
            </a>

            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 mt-6">Quản lý</p>

            <a href="{{ route('admin.orders.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/orders*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-shopping-cart w-5 text-center"></i>
                <span>Đơn hàng</span>
                <span class="ml-auto bg-indigo-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">New</span>
            </a>
            
            <a href="{{ route('admin.inventories.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/inventories*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-warehouse w-5 text-center"></i>
                <span>Tồn kho</span>
            </a>
            
            <a href="{{ route('admin.users.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/users*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Người dùng</span>
            </a>

            <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 mt-6">Khác</p>

            <a href="{{ route('admin.images.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/images*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-images w-5 text-center"></i>
                <span>Ảnh sản phẩm</span>
            </a>

            <a href="{{ route('admin.reports.index') }}" 
               class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                      {{ request()->is('admin/reports*') ? 'bg-indigo-500/20 text-indigo-400' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                <i class="fas fa-chart-bar w-5 text-center"></i>
                <span>Báo cáo</span>
            </a>
        </nav>

        <!-- User footer -->
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt w-4"></i>
                    Đăng xuất
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-64">
        <!-- Topbar -->
        <header class="sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="text-sm">
                        @yield('breadcrumb')
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="hidden sm:flex items-center">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" placeholder="Tìm kiếm..." 
                                   class="pl-10 pr-4 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64">
                        </div>
                    </div>

                    <!-- Notifications -->
                    <button class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-lg">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User dropdown -->
                    <div class="flex items-center gap-2 text-sm">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-xs font-semibold text-indigo-600">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                            </span>
                        </div>
                        <span class="hidden sm:inline font-medium text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-6">
            @if (session('success'))
                <div class="toast fixed top-20 right-6 z-50 bg-emerald-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2" id="flash-toast">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-2 hover:text-white/80">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <script>setTimeout(() => document.getElementById('flash-toast')?.remove(), 4000)</script>
            @endif

            @if (session('error'))
                <div class="toast fixed top-20 right-6 z-50 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2" id="flash-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="ml-2 hover:text-white/80">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <script>setTimeout(() => document.getElementById('flash-error')?.remove(), 4000)</script>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Toast Container -->
<div class="toast fixed bottom-6 right-6 z-50 bg-slate-800 text-white px-4 py-3 rounded-lg shadow-lg hidden" id="toast">
    <div class="flex items-center gap-2">
        <i class="fas fa-info-circle"></i>
        <span id="toast-message"></span>
        <button onclick="document.getElementById('toast').classList.add('hidden')" class="ml-2 hover:text-white/80">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
function showToast(msg, ok = true) {
    const t = document.getElementById('toast');
    const icon = t.querySelector('i');
    const message = document.getElementById('toast-message');
    
    message.textContent = msg;
    t.style.backgroundColor = ok ? '#059669' : '#ef4444';
    icon.className = ok ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 3000);
}
</script>

@stack('scripts')
</body>
</html>