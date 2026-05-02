@extends('layouts.user')

@section('title', 'Trang chủ')

@push('styles')
<style>
/* BUTTON */
.hk-btn { 
  padding: 0.5rem 0.75rem;
  font-size: 0.9rem;
  background: #111827; 
  border-radius: 0.5rem;
  color: #fff;
  font-weight: 600;
  transition: .3s; 
}
.hk-btn:hover { background: #000; transform: translateY(-2px); }

.hk-ghost { 
  border: 1px solid #111827; 
  padding: 0.5rem 0.75rem;
  font-size: 0.9rem;
  border-radius: 0.5rem; 
  background: #fff; 
  color: #111827; 
  transition: .3s; 
}
.hk-ghost:hover { background: #f3f4f6; }

/* CARD */
.hk-card { 
  box-shadow: 0 6px 16px rgba(0,0,0,.08); 
  transition: .3s; 
}
.hk-card:hover { transform: translateY(-4px); }

/* IMAGE */
.product-thumb { 
  width: 100%; 
  height: 220px; 
  object-fit: cover; 
  border-radius: .5rem; 
  border: 1px solid #e5e7eb;
}

/* RATING */
.rating-star { color: #d1d5db; }
.rating-star.filled { color: #f59e0b; }

/* TOAST */
.hk-toast { 
  position: fixed; right: 1rem; top: 5rem; z-index: 50; 
  background: #111827; color: #fff; 
  padding: .75rem 1rem; 
  border-radius: .5rem; 
  opacity: 0; transform: translateY(-8px); 
  transition: .25s 
}
.hk-toast.show { opacity: 1; transform: none; }
</style>
@endpush

@section('content')
<div class="bg-white hk-card rounded-lg p-6">

  {{-- HEADER --}}
  <div class="flex items-center justify-between gap-3 flex-wrap">
    <h1 class="text-2xl font-bold">🏍️ Danh sách xe</h1>

    <div class="flex gap-2 flex-wrap">
      <input id="q" placeholder="Tìm theo tên xe…" 
        class="border rounded px-3 py-2 w-64" />

      <select id="category" class="border rounded px-3 py-2">
        <option value="">Tất cả loại xe</option>
      </select>

      <button id="btnSearch" class="hk-ghost">Tìm</button>
    </div>
  </div>

  {{-- GRID --}}
  <div id="grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6"></div>

  {{-- PAGINATION --}}
  <div class="mt-6 flex justify-between items-center">
    <p id="meta" class="text-gray-500 text-sm">—</p>
    <div class="flex gap-2">
      <button id="prev" class="hk-ghost">← Trước</button>
      <button id="next" class="hk-ghost">Sau →</button>
    </div>
  </div>
</div>

<div id="toast" class="hk-toast"><span id="toastText"></span></div>
@endsection

@push('scripts')
<script>
const API = {
  products: "{{ route('public.products.list') }}",
  addCart: "{{ route('cart.add') }}",
  listCategories: "{{ route('public.categories.list') }}",
};

const token = "{{ csrf_token() }}";
const qs = s => document.querySelector(s);
let current = 1, last = 1;

// TOAST
const toast = (m, ok = true) => {
  const t = qs('#toast');
  t.style.background = ok ? '#111827' : '#dc2626';
  qs('#toastText').textContent = m?.message || m || 'OK';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2500);
};

// LOAD CATEGORY
async function loadCategories() {
  const j = await fetch(API.listCategories).then(r => r.json()).catch(() => ({ data: [] }));
  (j.data || []).forEach(c => {
    qs('#category').insertAdjacentHTML('beforeend', `<option value="${c.id}">${c.name}</option>`);
  });
}

// STAR
function renderStars(rating) {
  rating = Number(rating) || 0;
  let html = '';
  for (let i = 1; i <= 5; i++) {
    html += `<span class="${i <= Math.round(rating) ? 'rating-star filled' : 'rating-star'}">★</span>`;
  }
  return html;
}

// CARD
function card(p) {
  const price = (p.sale_price ?? p.price);
  const sale = p.sale_price ? `<span class="line-through text-gray-400 text-sm ml-1">${Number(p.price).toLocaleString('vi-VN')}₫</span>` : '';
  const stock = p.inventory?.stock ?? 0;

  return `
  <div class="bg-white rounded-lg p-4 hk-card">
    <img src="${p.thumbnail || ''}" class="product-thumb">

    <h3 class="font-semibold mt-2 line-clamp-2">${p.name}</h3>
    <p class="text-sm text-gray-500">${p.brand?.name ?? ''}</p>

    <div class="mt-1 text-sm">${renderStars(p.average_rating)} (${p.reviews?.length || 0})</div>

    <div class="mt-2 text-red-600 font-semibold">
      ${Number(price || 0).toLocaleString('vi-VN')}₫ ${sale}
    </div>

    <div class="text-xs mt-1">${stock > 0 ? `Còn ${stock}` : 'Hết hàng'}</div>

    <div class="mt-3 flex gap-2">
      <a href="/products/${p.id}" class="hk-ghost w-full text-center">Chi tiết</a>
      <button class="hk-btn w-full" onclick="add(${p.id}, ${stock})" ${stock <= 0 ? 'disabled' : ''}>
        Mua
      </button>
    </div>
  </div>`;
}

// FETCH
async function fetchList(page = 1) {
  const url = new URL(API.products, location.origin);

  const q = qs('#q').value.trim();
  const cat = qs('#category').value;

  if (q) url.searchParams.set('q', q);
  if (cat) url.searchParams.set('category_id', cat);

  url.searchParams.set('page', page);

  const j = await fetch(url).then(r => r.json());

  const grid = qs('#grid');
  grid.innerHTML = '';

  (j.data || []).forEach(p => grid.insertAdjacentHTML('beforeend', card(p)));

  current = j.meta.current_page;
  last = j.meta.last_page;

  qs('#meta').textContent = `Trang ${current}/${last}`;
}

// ADD CART
async function add(id, stock) {
  if (stock <= 0) return toast('Hết hàng', false);

  const res = await fetch(API.addCart, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({ product_id: id, quantity: 1 })
  });

  const j = await res.json();
  toast(j.ok ? 'Đã thêm vào giỏ' : 'Lỗi', j.ok);
}

// EVENTS
qs('#btnSearch').onclick = () => fetchList(1);
qs('#prev').onclick = () => current > 1 && fetchList(current - 1);
qs('#next').onclick = () => current < last && fetchList(current + 1);

// INIT
(async () => {
  await loadCategories();
  await fetchList();
})();
</script>
@endpush