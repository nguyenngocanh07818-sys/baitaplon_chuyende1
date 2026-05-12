@extends('layouts.user')

@section('title', 'Quản lý tài khoản')

@push('styles')
<style>
.hk-btn { 
  @apply px-5 py-3 text-sm font-semibold text-white; 
  background: #ff7eb8; 
  border-radius: 10px; 
  transition: .3s; 
   padding: 0.4rem 0.5rem;
}
.hk-btn:hover { background: #ff5fa7; transform: translateY(-2px); }

.hk-ghost { 
  @apply px-5 py-3 text-sm font-semibold; 
  border: 2px solid #ffc2dd; 
  border-radius: 10px; 
  background: #fff0f6; 
  color: #8b1c49; 
  transition: .3s; 
   padding: 0.4rem 0.5rem;
}
.hk-ghost:hover { background: #ffe4f1; }

.hk-card { 
  box-shadow: 0 8px 20px rgba(255,126,184,.15); 
  transition: .3s; 
}

.hk-toast { 
  position: fixed; right: 1rem; top: 5rem; z-index: 50; 
  background: #8b1c49; color: #fff; 
  padding: .75rem 1rem; 
  border-radius: .75rem; 
  box-shadow: 0 10px 20px rgba(255,126,184,.25); 
  opacity: 0; transform: translateY(-8px); 
  transition: .25s; 
}
.hk-toast.show { opacity: 1; transform: none; }

.modal { 
  display: none; 
  position: fixed; 
  top: 0; 
  left: 0; 
  width: 100%; 
  height: 100%; 
  background: rgba(0, 0, 0, 0.5); 
  z-index: 100; 
}
.modal.show { display: flex; justify-content: center; align-items: center; }
.modal-content { 
  background: white; 
  padding: 1.5rem; 
  border-radius: 0.75rem; 
  width: 100%; 
  max-width: 500px; 
}
</style>
@endpush

@section('content')
<div class="bg-white hk-card rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Quản lý tài khoản</h1>

    <div class="mb-6">
        <h2 class="text-lg font-semibold mb-2">Thông tin cá nhân</h2>
        <p><strong>Họ và tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <button id="edit-name-btn" class="mt-3 hk-btn">Đổi tên</button>
        <button id="edit-password-btn" class="mt-3 hk-btn">Đổi mật khẩu</button>
    </div>

    <a href="{{ route('user.home') }}" class="inline-block hk-ghost">Quay lại trang chủ</a>
</div>

<!-- Modal đổi tên -->
<div id="name-modal" class="modal">
    <div class="modal-content">
        <h2 class="text-lg font-semibold mb-4">Đổi tên</h2>
        <form id="name-form">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">Họ và tên</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}"
                       class="mt-1 block w-full border rounded-md px-3 py-2 border-slate-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
                       required />
                <p id="name-error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="hk-btn">Cập nhật</button>
                <button type="button" id="name-cancel" class="hk-ghost">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal đổi mật khẩu -->
<div id="password-modal" class="modal">
    <div class="modal-content">
        <h2 class="text-lg font-semibold mb-4">Đổi mật khẩu</h2>
        <form id="password-form">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-slate-700">Mật khẩu hiện tại</label>
                <input type="password" name="current_password" id="current_password"
                       class="mt-1 block w-full border rounded-md px-3 py-2 border-slate-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
                       required />
                <p id="current_password-error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-slate-700">Mật khẩu mới</label>
                <input type="password" name="password" id="password"
                       class="mt-1 block w-full border rounded-md px-3 py-2 border-slate-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
                       required />
                <p id="password-error" class="text-red-500 text-xs mt-1 hidden"></p>
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Xác nhận mật khẩu mới</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="mt-1 block w-full border rounded-md px-3 py-2 border-slate-300 focus:outline-none focus:ring-2 focus:ring-pink-500"
                       required />
            </div>
            <div class="flex gap-3">
                <button type="submit" class="hk-btn">Đổi mật khẩu</button>
                <button type="button" id="password-cancel" class="hk-ghost">Hủy</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="hk-toast"><span id="toastText">...</span></div>
@endsection

@push('scripts')
<script>
const API = {
    updateName: "{{ route('user.account.update') }}",
    updatePassword: "{{ route('user.account.password') }}",
};
const token = "{{ csrf_token() }}";

const toast = (m, ok = true) => {
    const t = document.querySelector('#toast');
    t.style.background = ok ? '#8b1c49' : '#ff6b8a';
    document.querySelector('#toastText').textContent = (typeof m === 'string') ? m : (m?.message || 'OK');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
};

const showModal = (id) => {
    document.querySelector(id).classList.add('show');
};
const hideModal = (id) => {
    document.querySelector(id).classList.remove('show');
    document.querySelectorAll(`${id} .text-red-500`).forEach(el => el.classList.add('hidden'));
    document.querySelector(id).querySelector('form').reset();
};

// Đổi tên
document.getElementById('edit-name-btn').addEventListener('click', () => showModal('#name-modal'));
document.getElementById('name-cancel').addEventListener('click', () => hideModal('#name-modal'));

document.getElementById('name-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const name = form.querySelector('#name').value.trim();
    
    if (!name) {
        document.getElementById('name-error').textContent = 'Họ và tên không được để trống';
        document.getElementById('name-error').classList.remove('hidden');
        return;
    }

    try {
        const res = await fetch(API.updateName, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ name })
        });
        const j = await res.json();
        if (j.ok) {
            toast(j.message || 'Cập nhật tên thành công');
            hideModal('#name-modal');
            window.location.reload(); // Reload để cập nhật tên trên giao diện
        } else {
            document.getElementById('name-error').textContent = j.message || 'Lỗi khi cập nhật';
            document.getElementById('name-error').classList.remove('hidden');
        }
    } catch (error) {
        toast('Đã có lỗi xảy ra', false);
    }
});

// Đổi mật khẩu
document.getElementById('edit-password-btn').addEventListener('click', () => showModal('#password-modal'));
document.getElementById('password-cancel').addEventListener('click', () => hideModal('#password-modal'));

document.getElementById('password-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const current_password = form.querySelector('#current_password').value;
    const password = form.querySelector('#password').value;
    const password_confirmation = form.querySelector('#password_confirmation').value;

    if (password.length < 8) {
        document.getElementById('password-error').textContent = 'Mật khẩu mới phải có ít nhất 8 ký tự';
        document.getElementById('password-error').classList.remove('hidden');
        return;
    }
    if (password !== password_confirmation) {
        document.getElementById('password-error').textContent = 'Mật khẩu xác nhận không khớp';
        document.getElementById('password-error').classList.remove('hidden');
        return;
    }

    try {
        const res = await fetch(API.updatePassword, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ current_password, password, password_confirmation })
        });
        const j = await res.json();
        if (j.ok) {
            toast(j.message || 'Đổi mật khẩu thành công');
            hideModal('#password-modal');
        } else {
            document.getElementById('current_password-error').textContent = j.message || 'Lỗi khi đổi mật khẩu';
            document.getElementById('current_password-error').classList.remove('hidden');
        }
    } catch (error) {
        toast('Đã có lỗi xảy ra', false);
    }
});
</script>
@endpush