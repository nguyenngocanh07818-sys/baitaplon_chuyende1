@extends('layouts.user')
@section('title','Đăng ký')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
  <div class="w-full max-w-md">
    <div class="bg-white shadow-lg rounded-2xl p-8 border border-slate-200 transition-transform duration-300 hover:-translate-y-0.5">
      <h2 class="text-2xl font-semibold mb-1">Tạo tài khoản ✨</h2>
      <p class="text-slate-500 mb-6">Miễn phí & nhanh chóng</p>

      @if ($errors->any())
        <div class="mb-4 rounded border border-rose-300 bg-rose-50 px-4 py-3 text-rose-900">
          <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if (session('success'))
        <div class="mb-4 rounded border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900">
          {{ session('success') }}
        </div>
      @endif

      <form id="registerForm" method="POST" action="{{ route('register.perform') }}" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm mb-1">Họ tên</label>
          <input type="text" name="name" value="{{ old('name') }}" required
                 class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500" />
        </div>

        <div>
          <label class="block text-sm mb-1">Email</label>
          <input id="emailInput" type="email" name="email" value="{{ old('email') }}" required
                 class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500" />
        </div>

        <div class="grid grid-cols-3 gap-3 items-end">
          <div class="col-span-2">
            <label class="block text-sm mb-1">Mã xác nhận (6 số)</label>
            <input id="codeInput" type="text" name="code" maxlength="6" inputmode="numeric" pattern="\d{6}" required
                   class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500" placeholder="••••••" />
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
                 class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500" />
        </div>

        <div>
          <label class="block text-sm mb-1">Nhập lại mật khẩu</label>
          <input type="password" name="password_confirmation" required
                 class="w-full rounded-lg border-slate-300 focus:border-slate-500 focus:ring-slate-500" />
        </div>

        <button type="submit"
                class="w-full py-2.5 rounded-lg bg-slate-900 text-white hover:bg-slate-800 active:scale-[.99] transition">
          Đăng ký
        </button>
      </form>

      <div class="text-sm text-center text-slate-500 mt-6">
        Đã có tài khoản?
        <a class="text-slate-900 hover:underline" href="{{ route('login') }}">Đăng nhập</a>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const btn = document.getElementById('getCodeBtn');
  const emailInput = document.getElementById('emailInput');
  const msg = document.getElementById('codeMsg');
  const remainInit = {{ (int)($cooldownRemain ?? 0) }}; // từ controller
  let remaining = remainInit > 0 ? remainInit : 0;
  let timer = null;

  function updateBtn() {
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
      if (remaining <= 0) {
        clearInterval(timer);
      }
    }, 1000);
  }

  // Khởi tạo nếu còn cooldown
  if (remaining > 0) startCountdown(remaining); else updateBtn();

  btn.addEventListener('click', async () => {
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
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ email })
      });

      const data = await res.json();

      if (res.ok && data.ok) {
        msg.textContent = data.message || 'Đã gửi mã.';
        startCountdown(data.remain ?? 30);
      } else {
        msg.textContent = data.message || 'Không thể gửi mã.';
        if (typeof data.remain === 'number' && data.remain > 0) {
          startCountdown(data.remain);
        }
      }
    } catch (e) {
      msg.textContent = 'Lỗi mạng. Thử lại sau.';
    }
  });
})();
</script>
@endsection
