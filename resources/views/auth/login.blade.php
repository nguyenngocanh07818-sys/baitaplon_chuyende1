@extends('layouts.user')
@section('title','Xác thực tài khoản')

@section('content')
<style>
  /* Hiệu ứng flip card */
  .perspective { perspective: 1500px; }
  .preserve-3d { transform-style: preserve-3d; }
  .backface-hidden { backface-visibility: hidden; }
  .rotate-y-180 { transform: rotateY(180deg); }
</style>

<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

  <!-- Bên trái: Background + slogan -->
  <div class="relative bg-cover bg-center flex items-top justify-center text-center p-10"
       style="background-image: url('https://i0.wp.com/tasteandtipple.ca/wp-content/uploads/2023/12/4K7A0715-scaled.jpeg?resize=960%2C1498&ssl=1');">
    <div class="relative z-10 text-white max-w-md">
      <h1 class="text-4xl font-extrabold mb-4 tracking-wide" style="font-family:'Playfair Display', serif;">
        WINE SHOP
      </h1>
      <p class="text-lg font-light" style="font-family:'Playfair Display', serif;">
        🍷 Khám phá hương vị tuyệt hảo từ những dòng rượu vang đẳng cấp.
      </p>
      <p style="font-family:'Playfair Display', serif;">
        Đăng nhập hoặc tạo tài khoản để tận hưởng dịch vụ và ưu đãi dành riêng cho bạn.
      </p>
    </div>
  </div>

  <!-- Bên phải: Flip card -->
  <div class="bg-white h-screen flex items-top justify-center p-6">
    <div class="relative w-full max-w-md perspective">

      <!-- Card container -->
      <div id="auth-card" class="relative preserve-3d duration-700 w-full" style="transition: transform 0.8s;">

        <!-- Mặt trước: Login -->
        <div class="absolute backface-hidden w-full p-8" id="login-face">
          <h2 class="text-3xl font-bold text-[#941B2B] mb-6 text-center">Đăng nhập</h2>

          <form id="loginForm" method="POST" action="{{ route('login.perform') }}" class="space-y-5">
            @csrf
            <div>
              <label class="block text-sm font-medium mb-1">Email</label>
              <input type="email" name="email" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <div>
              <label class="block text-sm font-medium mb-1">Mật khẩu</label>
              <input type="password" name="password" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <label class="inline-flex items-center gap-2 text-sm">
              <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#941B2B] focus:ring-[#941B2B]">
              Ghi nhớ đăng nhập
            </label>
            <button type="submit"
                    class="w-full py-3 rounded-lg bg-[#941B2B] text-white font-semibold hover:bg-[#7a1523] transition">
              Đăng nhập
            </button>
          </form>

          <p class="text-sm text-center text-slate-600 mt-6">
            Chưa có tài khoản?
            <button id="showRegister" class="text-[#941B2B] font-semibold hover:underline">Đăng ký ngay</button>
          </p>
        </div>

        <!-- Mặt sau: Register -->
        <div class="absolute backface-hidden w-full p-8 rotate-y-180" id="register-face">
          <h2 class="text-3xl font-bold text-[#941B2B] mb-6 text-center">Đăng ký</h2>

          <form id="registerForm" method="POST" action="{{ route('register.perform') }}" class="space-y-5">
            @csrf
            <div>
              <label class="block text-sm mb-1">Họ tên</label>
              <input type="text" name="name" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <div>
              <label class="block text-sm mb-1">Email</label>
              <input id="emailInput" type="email" name="email" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <div class="grid grid-cols-3 gap-3 items-end">
              <div class="col-span-2">
                <label class="block text-sm mb-1">Mã xác nhận (6 số)</label>
                <input id="codeInput" type="text" name="code" maxlength="6" inputmode="numeric" pattern="\d{6}" required
                       class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" placeholder="••••••" />
              </div>
              <div class="col-span-1">
                <button id="getCodeBtn" type="button"
                        class="w-full py-2.5 rounded-lg border border-slate-300 hover:bg-slate-100 transition disabled:opacity-50">
                  Lấy mã
                </button>
                <div id="codeMsg" class="text-xs text-slate-500 mt-1"></div>
              </div>
            </div>
            <div>
              <label class="block text-sm mb-1">Mật khẩu</label>
              <input type="password" name="password" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <div>
              <label class="block text-sm mb-1">Nhập lại mật khẩu</label>
              <input type="password" name="password_confirmation" required
                     class="w-full border rounded-lg px-3 py-2 border-slate-300 focus:border-[#941B2B] focus:ring-[#941B2B]" />
            </div>
            <button type="submit"
                    class="w-full py-3 rounded-lg bg-[#941B2B] text-white font-semibold hover:bg-[#7a1523] transition">
              Đăng ký
            </button>
          </form>

          <p class="text-sm text-center text-slate-600 mt-6">
            Đã có tài khoản?
            <button id="showLogin" class="text-[#941B2B] font-semibold hover:underline">Đăng nhập</button>
          </p>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const card = document.getElementById('auth-card');
  const showRegister = document.getElementById('showRegister');
  const showLogin = document.getElementById('showLogin');

  // Flip hiệu ứng
  showRegister?.addEventListener('click', () => card.style.transform = "rotateY(180deg)");
  showLogin?.addEventListener('click', () => card.style.transform = "rotateY(0deg)");

  const token = '{{ csrf_token() }}';

  // Toast helper
  function showToast(msg, ok = true) {
    let toast = document.getElementById('toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast';
      toast.className = 'fixed top-5 right-5 px-4 py-3 rounded-lg text-white shadow-lg transition opacity-0';
      document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.background = ok ? '#941B2B' : '#dc2626';
    toast.classList.remove('opacity-0');
    setTimeout(() => toast.classList.add('opacity-0'), 3000);
  }

  // Gửi mã xác nhận
  const btn = document.getElementById('getCodeBtn');
  const emailInput = document.getElementById('emailInput');
  const msg = document.getElementById('codeMsg');
  const remainInit = {{ (int)($cooldownRemain ?? 0) }};
  let remaining = remainInit > 0 ? remainInit : 0;
  let timer = null;

  function updateBtn() {
    if (!btn) return;
    if (remaining > 0) {
      btn.disabled = true;
      btn.textContent = `Lấy mã (${remaining}s)`;
    } else {
      btn.disabled = false;
      btn.textContent = 'Lấy mã';
    }
  }
  function startCountdown(sec) {
    remaining = sec;
    updateBtn();
    if (timer) clearInterval(timer);
    timer = setInterval(() => {
      remaining--;
      updateBtn();
      if (remaining <= 0) clearInterval(timer);
    }, 1000);
  }
  if (remaining > 0) startCountdown(remaining); else updateBtn();

  btn?.addEventListener('click', async () => {
    msg.textContent = '';
    const email = emailInput.value.trim();
    if (!email) {
      msg.textContent = 'Nhập email trước khi lấy mã.';
      return;
    }
    try {
      const res = await fetch('{{ route('register.send_code') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ email })
      });
      const data = await res.json();
      if (res.ok && data.ok) {
        msg.textContent = data.message || 'Đã gửi mã.';
        startCountdown(data.remain ?? 30);
        showToast('Mã xác nhận đã gửi đến email');
      } else {
        msg.textContent = data.message || 'Không thể gửi mã.';
        if (typeof data.remain === 'number' && data.remain > 0) startCountdown(data.remain);
        showToast(data.message || 'Không thể gửi mã', false);
      }
    } catch (e) {
      msg.textContent = 'Lỗi mạng. Thử lại sau.';
      showToast('Lỗi mạng khi gửi mã', false);
    }
  });
})();
</script>
@endsection
