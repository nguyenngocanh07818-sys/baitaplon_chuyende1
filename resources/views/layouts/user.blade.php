<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>User | @yield('title','Trang chủ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        .contact-float {
          position: fixed;
          right: 1rem;
          bottom: 2rem;
          display: flex;
          flex-direction: column;
          gap: .75rem;
          z-index: 999;
        }

        .contact-item {
          background: #111827;
          color: #fff;
          font-size: 0.9rem;
          font-weight: 600;
          padding: .8rem;
          border-radius: 50px;
          display: flex;
          align-items: center;
          gap: .6rem;
          text-decoration: none;
          box-shadow: 0 4px 12px rgba(0,0,0,.2);
          transition: all 0.35s ease;
          overflow: hidden;
          width: 48px;
        }

        .contact-item span {
          white-space: nowrap;
          opacity: 0;
          transform: translateX(10px);
          transition: all 0.3s ease;
        }

        .contact-item i, 
        .contact-item img {
          width: 24px;
          height: 24px;
        }

        .contact-item:hover {
          width: 160px;
          padding: .8rem 1rem;
        }

        .contact-item:hover span {
          opacity: 1;
          transform: translateX(0);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-100">

<!-- NAVBAR -->
<nav class="bg-gray-900 text-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">

        <!-- LEFT -->
        <div class="flex gap-6 items-center">
            <a href="{{ route('user.home') }}" class="font-semibold hover:text-gray-300">Trang chủ</a>

            <a href="{{ route('user.cart') }}" class="hover:text-gray-300">Giỏ hàng</a>
            <a href="{{ route('user.orders') }}" class="hover:text-gray-300">Đơn hàng</a>
        </div>

        <!-- LOGO -->
        <div class="text-2xl font-bold tracking-wide">
            MOTORBIKE SHOP
        </div>

        <!-- RIGHT -->
        <div class="flex items-center gap-3">
            @auth
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center gap-2">
                        {{ auth()->user()->name }}
                        <i class="fa fa-chevron-down text-xs"></i>
                    </button>

                    <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded shadow">
                        <a href="{{ route('user.account') }}" class="block px-4 py-2 hover:bg-gray-100">Tài khoản</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100">Đăng xuất</button>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="px-3 py-1 bg-white text-black rounded">Đăng nhập</a>
                <a href="{{ route('register') }}" class="px-3 py-1 border border-white rounded">Đăng ký</a>
            @endguest
        </div>
    </div>
</nav>

<!-- CONTENT -->
<div class="max-w-6xl mx-auto p-4">

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</div>

<!-- CONTACT FLOAT -->
<div class="contact-float">
    <a href="#" class="contact-item">
        <i class="fa fa-phone"></i>
        <span>Hotline</span>
    </a>
    <a href="#" class="contact-item">
        <i class="fa-brands fa-facebook-messenger"></i>
        <span>Messenger</span>
    </a>
</div>

<!-- FOOTER -->
<footer class="bg-white border-t mt-10">
    <div class="max-w-7xl mx-auto px-4 py-10 grid md:grid-cols-4 gap-8 text-sm">

        <div>
            <h3 class="font-bold mb-3">MOTORBIKE SHOP</h3>
            <p>Chuyên phân phối xe máy chính hãng.</p>
            <p class="mt-2">Hotline: <b>0123456789</b></p>
        </div>

        <div>
            <h3 class="font-bold mb-3">DANH MỤC XE</h3>
            <p>Xe số</p>
            <p>Xe tay ga</p>
            <p>Xe côn tay</p>
            <p>Xe điện</p>
        </div>

        <div>
            <h3 class="font-bold mb-3">HÃNG XE</h3>
            <p>Honda</p>
            <p>Yamaha</p>
            <p>Suzuki</p>
            <p>VinFast</p>
        </div>

        <div>
            <h3 class="font-bold mb-3">HỖ TRỢ</h3>
            <p>Bảo hành</p>
            <p>Trả góp</p>
            <p>Liên hệ</p>
        </div>

    </div>

    <div class="text-center text-xs text-gray-500 py-4 border-t">
        © {{ date('Y') }} Motorbike Shop
    </div>
</footer>

<script>
    const btn = document.getElementById('user-menu-button');
    if(btn){
        btn.addEventListener('click', () => {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            const menu = document.getElementById('user-menu');
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    }
</script>

@stack('scripts')
</body>
</html>