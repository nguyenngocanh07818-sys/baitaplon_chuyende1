@extends('layouts.user')
@section('title', 'Xác thực tài khoản - Motorbike Shop')

@section('content')
<style>
  .moto-red { color: #E30613; }
  
  .auth-card {
    background: white;
    border: 1px solid #e5e5e5;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
  }
  .auth-card:hover {
    box-shadow: 0 15px 40px rgba(227, 6, 19, 0.1);
  }

  .input-moto {
    border: 2px solid #e5e5e5;
    transition: all 0.3s;
  }
  .input-moto:focus {
    border-color: #E30613;
    box-shadow: 0 0 0 4px rgba(227, 6, 19, 0.12);
    outline: none;
  }

  .btn-moto {
    background-color: #E30613;
    transition: all 0.3s ease;
  }
  .btn-moto:hover {
    background-color: #C8102E;
    transform: translateY(-2px);
  }
</style>

<div class="min-h-screen bg-white flex items-center justify-center p-6">
  <div class="w-full max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- ==================== CARD ĐĂNG NHẬP ==================== -->
      <div class="auth-card rounded-3xl p-10">
        <div class="flex justify-center mb-6">
          <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-4xl">
            🏍️
          </div>
        </div>
        
        <h2 class="text-3xl font-bold text-center moto-red mb-2">ĐĂNG NHẬP</h2>
        <p class="text-center text-zinc-500 text-sm mb-8">Chào mừng bạn trở lại với Motorbike Shop</p>

        <form id="loginForm" method="POST" action="{{ route('login.perform') }}" class="space-y-6">
          @csrf
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Email hoặc Số điện thoại</label>
            <input type="text" name="email" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base" 
                   placeholder="Nhập email hoặc số điện thoại">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Mật khẩu</label>
            <input type="password" name="password" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base" 
                   placeholder="Nhập mật khẩu">
          </div>
          
          <div class="flex items-center justify-between text-sm">
            <label class="inline-flex items-center gap-2">
              <input type="checkbox" name="remember" class="w-4 h-4 text-[#E30613]">
              Ghi nhớ đăng nhập
            </label>
            <a href="#" class="moto-red hover:underline">Quên mật khẩu?</a>
          </div>
          
          <button type="submit" 
                  class="btn-moto w-full py-4 rounded-2xl text-white font-bold text-lg">
            ĐĂNG NHẬP
          </button>
        </form>
        
        <p class="text-center text-zinc-500 mt-8 text-sm">
          Chưa có tài khoản? 
          <a href="#" id="switchToRegister" class="moto-red font-semibold hover:underline">Đăng ký ngay</a>
        </p>
      </div>

      <!-- ==================== CARD ĐĂNG KÝ ==================== -->
      <div class="auth-card rounded-3xl p-10">
        <div class="flex justify-center mb-6">
          <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center text-4xl">
            🏍️
          </div>
        </div>
        
        <h2 class="text-3xl font-bold text-center moto-red mb-2">ĐĂNG KÝ</h2>
        <p class="text-center text-zinc-500 text-sm mb-8">Tạo tài khoản để nhận nhiều ưu đãi hấp dẫn</p>

        <form id="registerForm" method="POST" action="{{ route('register.perform') }}" class="space-y-6">
          @csrf
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Họ và tên</label>
            <input type="text" name="name" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Email</label>
            <input id="emailInput" type="email" name="email" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base">
          </div>
          
          <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
              <label class="block text-sm font-semibold text-zinc-700 mb-2">Mã xác nhận (6 số)</label>
              <input id="codeInput" type="text" name="code" maxlength="6" inputmode="numeric"
                     class="input-moto w-full rounded-2xl px-6 py-4 text-base" 
                     placeholder="••••••" required>
            </div>
            <div class="flex flex-col justify-end">
              <button id="getCodeBtn" type="button"
                      class="h-12 rounded-2xl border border-zinc-300 hover:bg-zinc-100 font-medium transition disabled:opacity-60">
                Lấy mã
              </button>
              <div id="codeMsg" class="text-xs text-center text-zinc-500 mt-1"></div>
            </div>
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Mật khẩu</label>
            <input type="password" name="password" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base">
          </div>
          
          <div>
            <label class="block text-sm font-semibold text-zinc-700 mb-2">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" required 
                   class="input-moto w-full rounded-2xl px-6 py-4 text-base">
          </div>
          
          <button type="submit" 
                  class="btn-moto w-full py-4 rounded-2xl text-white font-bold text-lg">
            TẠO TÀI KHOẢN
          </button>
        </form>
        
        <p class="text-center text-zinc-500 mt-8 text-sm">
          Đã có tài khoản? 
          <a href="#" id="switchToLogin" class="moto-red font-semibold hover:underline">Đăng nhập</a>
        </p>
      </div>

    </div>
  </div>
</div>

<script>
(function(){
  const token = '{{ csrf_token() }}';

  // Chuyển đổi giữa 2 card (không dùng flip)
  document.getElementById('switchToRegister')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('registerForm').scrollIntoView({ behavior: 'smooth' });
  });

  document.getElementById('switchToLogin')?.addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('loginForm').scrollIntoView({ behavior: 'smooth' });
  });

  // Toast thông báo
  function showToast(msg, ok = true) {
    let toast = document.getElementById('toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'toast';
      toast.className = 'fixed top-6 right-6 px-6 py-4 rounded-2xl text-white shadow-2xl z-50';
      document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.backgroundColor = ok ? '#E30613' : '#dc2626';
    toast.style.opacity = '1';
    setTimeout(() => toast.style.opacity = '0', 3500);
  }

  // Lấy mã xác nhận
  const btn = document.getElementById('getCodeBtn');
  const emailInput = document.getElementById('emailInput');
  const msg = document.getElementById('codeMsg');
  let remaining = {{ (int)($cooldownRemain ?? 0) }};
  let timer = null;

  function updateBtn() {
    if (!btn) return;
    btn.textContent = remaining > 0 ? `(${remaining}s)` : 'Lấy mã';
    btn.disabled = remaining > 0;
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

  if (remaining > 0) startCountdown(remaining);
  else updateBtn();

  btn?.addEventListener('click', async () => {
    msg.textContent = '';
    const email = emailInput.value.trim();
    if (!email) {
      msg.textContent = 'Vui lòng nhập email';
      return;
    }

    try {
      const res = await fetch('{{ route("register.send_code") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ email })
      });

      const data = await res.json();

      if (res.ok && data.ok) {
        msg.textContent = data.message || 'Mã đã gửi!';
        startCountdown(data.remain ?? 60);
        showToast('Mã xác nhận đã được gửi đến email');
      } else {
        msg.textContent = data.message || 'Không thể gửi mã';
        if (data.remain && data.remain > 0) startCountdown(data.remain);
        showToast(data.message || 'Có lỗi xảy ra', false);
      }
    } catch (e) {
      msg.textContent = 'Lỗi kết nối!';
      showToast('Lỗi mạng khi gửi mã', false);
    }
  });
})();
</script>
@endsection