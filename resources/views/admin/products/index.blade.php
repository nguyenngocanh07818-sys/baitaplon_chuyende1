@extends('layouts.admin')
@section('title','Sản phẩm')
@section('breadcrumb')
    <nav class="flex items-center gap-2 text-sm">
        <span class="text-slate-400"><i class="fas fa-home"></i></span>
        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
        <span class="text-slate-600 font-medium">Quản lý sản phẩm</span>
    </nav>
@endsection

@push('styles')
<style>
    .card-brand {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 20px;
    }
    .toolbar {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
        margin-bottom: 16px;
    }
    .search-input, .form-input, .form-select, .form-textarea {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        background: #fff;
    }
    .search-input:focus, .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .search-input { width: 100%; }
    .form-input, .form-select { width: 100%; }
    .form-textarea {
        width: 100%;
        resize: vertical;
        min-height: 120px;
    }
    .btn-primary {
        border: 0;
        background: #6366f1;
        color: #fff;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-primary:hover { background: #4f46e5; }
    .btn-ghost {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-ghost:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-danger {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-danger:hover { background: #fee2e2; }
    .btn-edit {
        background: #eef2ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
        border-radius: 10px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit:hover { background: #e0e7ff; }
    .table-brand {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 6px;
    }
    .table-brand th {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        text-align: left;
        padding: 8px 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .table-row {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        transition: all 0.2s;
    }
    .table-row:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .table-row td {
        padding: 12px;
        font-size: 14px;
        vertical-align: middle;
    }
    .table-row td:first-child { border-radius: 10px 0 0 10px; }
    .table-row td:last-child { border-radius: 0 10px 10px 10px; }
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 100;
    }
    .modal-overlay.show { display: flex; }
    .modal-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        padding: 24px;
        width: min(900px, 96vw);
        max-height: 90vh;
        overflow-y: auto;
    }
    .modal-panel h3 {
        margin: 0 0 16px;
        color: #1e293b;
        font-size: 18px;
        font-weight: 600;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }
    .form-grid label {
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        display: block;
        margin-bottom: 4px;
    }
    .toast-notification {
        position: fixed;
        right: 24px;
        top: 80px;
        background: #1e293b;
        color: #fff;
        padding: 12px 18px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.25s ease;
        z-index: 200;
        font-size: 14px;
        font-weight: 500;
    }
    .toast-notification.show { opacity: 1; transform: translateY(0); }
    .badge-id {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-status.active { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-status.draft { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-status.hidden { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .thumbnail-preview {
        width: 140px;
        height: 140px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 8px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .thumbnail-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thumb-cell {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        overflow: hidden;
        background: #f1f5f9;
    }
    .thumb-cell img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .text-right { text-align: right; }
    .text-muted { color: #94a3b8; }
    .font-mono { font-family: 'SF Mono', 'Fira Code', monospace; }
    .price-text { font-weight: 600; color: #1e293b; }
    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        grid-column: 1 / -1;
        padding-top: 8px;
        border-top: 1px solid #e2e8f0;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Sản phẩm</h1>
            <p class="text-sm text-slate-500 mt-1">Quản lý danh sách sản phẩm xe máy</p>
        </div>
        <button class="btn-primary" id="btnAdd">
            <i class="fas fa-plus mr-1.5"></i>Thêm sản phẩm
        </button>
    </div>

    {{-- Filters --}}
    <div class="card-brand">
        <div class="toolbar">
            <div class="relative" style="flex:1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input id="q" class="search-input" placeholder="Tìm theo tên, SKU..." style="padding-left: 36px;">
            </div>
            <select id="filterStatus" class="form-select" style="width: auto; min-width: 140px;">
                <option value="">Tất cả trạng thái</option>
                <option value="active">Hiển thị</option>
                <option value="draft">Nháp</option>
                <option value="hidden">Ẩn</option>
            </select>
            <select id="filterCategory" class="form-select" style="width: auto; min-width: 160px;">
                <option value="">Tất cả danh mục</option>
            </select>
            <button class="btn-ghost" id="btnSearch">
                <i class="fas fa-filter mr-1.5"></i>Lọc
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="card-brand" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto; padding: 20px;">
            <table class="table-brand">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Danh mục</th>
                        <th>Thương hiệu</th>
                        <th>Trạng thái</th>
                        <th>Kho</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal-overlay" id="modal">
    <div class="modal-panel">
        <div class="flex items-center justify-between mb-6">
            <h3 id="modalTitle" style="margin:0">Thêm sản phẩm mới</h3>
            <button onclick="closeModal()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="form" class="form-grid">
            <input type="hidden" id="id">

            {{-- Thông tin cơ bản --}}
            <div class="section-title">Thông tin cơ bản</div>

            <label>Tên sản phẩm <span style="color:#ef4444">*</span>
                <input id="name" class="form-input" placeholder="Nhập tên sản phẩm" required>
            </label>
            <label>Slug
                <input id="slug" class="form-input" placeholder="Để trống tự tạo" style="background:#f8fafc">
            </label>
            <label>SKU <span style="color:#ef4444">*</span>
                <input id="sku" class="form-input" placeholder="Mã sản phẩm" required>
            </label>

            <label>Danh mục
                <select id="category_id" class="form-select"></select>
            </label>
            <label>Thương hiệu
                <select id="brand_id" class="form-select"></select>
            </label>
            <label>Trạng thái
                <select id="status" class="form-select">
                    <option value="active">Hiển thị</option>
                    <option value="draft">Nháp</option>
                    <option value="hidden">Ẩn</option>
                </select>
            </label>

            {{-- Giá --}}
            <div class="section-title">Giá bán</div>

            <label>Giá bán (VNĐ)
                <input id="price" type="number" class="form-input" placeholder="VD: 50000000" step="1000">
            </label>
            <label>Giá khuyến mãi (VNĐ)
                <input id="sale_price" type="number" class="form-input" placeholder="Để trống nếu không có" step="1000">
            </label>
            <div></div>

            {{-- Thông số kỹ thuật --}}
            <div class="section-title">Thông số kỹ thuật xe máy</div>

            <label>Dung tích xi-lanh (cc)
                <input id="engine_capacity" class="form-input" placeholder="VD: 150">
            </label>
            <label>Loại nhiên liệu
                <select id="fuel_type" class="form-select">
                    <option value="">Chọn loại</option>
                    <option value="gasoline">Xăng</option>
                    <option value="electric">Điện</option>
                </select>
            </label>
            <label>Hộp số
                <select id="transmission" class="form-select">
                    <option value="">Chọn loại</option>
                    <option value="manual">Số tay</option>
                    <option value="automatic">Tự động</option>
                </select>
            </label>

            <label>Công suất (HP)
                <input id="power" class="form-input" placeholder="VD: 12.5">
            </label>
            <label>Trọng lượng (kg)
                <input id="weight" class="form-input" placeholder="VD: 120">
            </label>
            <label>Màu sắc
                <input id="color" class="form-input" placeholder="VD: Đỏ, Đen, Trắng">
            </label>

            {{-- Mô tả --}}
            <div class="section-title">Mô tả sản phẩm</div>
            <label style="grid-column:1/-1">
                <textarea id="description" class="form-textarea" placeholder="Mô tả chi tiết về sản phẩm..."></textarea>
            </label>

            {{-- Thumbnail --}}
            <div class="section-title">Ảnh đại diện</div>
            <div style="grid-column:1/-1">
                <label>Link ảnh đại diện
                    <input id="thumbnail" type="url" class="form-input" placeholder="https://example.com/image.jpg">
                </label>
                <div id="thumbnailPreview" class="thumbnail-preview">
                    <span class="text-muted text-sm">Chưa có ảnh</span>
                </div>
            </div>

            {{-- Buttons --}}
            <div style="grid-column:1/-1; display:flex; gap:10px; justify-content:flex-end; margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0;">
                <button type="button" class="btn-ghost" onclick="closeModal()">
                    <i class="fas fa-times mr-1.5"></i>Huỷ
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-1.5"></i>Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="toast-notification">
    <i class="fas fa-check-circle mr-2"></i>
    <span id="toastText"></span>
</div>
@endsection

@push('scripts')
<script>
const API = {
  list: "{{ route('admin.products.list') }}",
  store: "{{ route('admin.products.store') }}",
  update: id => "{{ route('admin.products.update', ':id') }}".replace(':id', id),
  delete: id => "{{ route('admin.products.destroy', ':id') }}".replace(':id', id),
  categories: "{{ route('admin.categories.list') }}",
  brands: "{{ route('admin.brands.list') }}"
};
const qs = s => document.querySelector(s);
const toast = (m, ok = true) => {
  const t = qs('#toast');
  t.style.background = ok ? '#1e293b' : '#ef4444';
  qs('#toastText').textContent = m?.message || m || 'OK';
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3000);
};

let dataCache = [];

function setupThumbnailPreview() {
  const thumbInput = qs('#thumbnail');
  const previewDiv = qs('#thumbnailPreview');
  thumbInput.addEventListener('input', () => {
    const url = thumbInput.value.trim();
    if (url) {
      previewDiv.innerHTML = `<img src="${url}" alt="preview" onerror="this.parentElement.innerHTML='<span class=\\'text-red-400 text-sm\\'>Lỗi tải ảnh</span>'">`;
    } else {
      previewDiv.innerHTML = '<span class="text-muted text-sm">Chưa có ảnh</span>';
    }
  });
}

async function loadCategories() {
  let r = await fetch(API.categories + '?per_page=200', {headers:{Accept:'application/json'}}).then(r => r.json());
  let html = '<option value="">Chọn danh mục</option>';
  (r.data || []).forEach(c => html += `<option value="${c.id}">${c.name}</option>`);
  qs('#filterCategory').innerHTML = '<option value="">Tất cả danh mục</option>' + html.replace('<option value="">Chọn danh mục</option>', '');
  qs('#category_id').innerHTML = html;
}

async function loadBrands() {
  let r = await fetch(API.brands + '?per_page=200', {headers:{Accept:'application/json'}}).then(r => r.json());
  let html = '<option value="">Chọn thương hiệu</option>';
  (r.data || []).forEach(b => html += `<option value="${b.id}">${b.name}</option>`);
  qs('#brand_id').innerHTML = html;
}

async function load() {
  let r = await fetch(API.list, {headers:{Accept:'application/json'}}).then(r => r.json());
  dataCache = r.data || [];
  render();
}

function render() {
  let tb = qs('#tbody');
  tb.innerHTML = '';
  
  if (!dataCache.length) {
    tb.innerHTML = `<tr><td colspan="9" class="text-center py-12 text-slate-400">
      <i class="fas fa-inbox text-3xl mb-2 block"></i>Không tìm thấy sản phẩm nào
    </td></tr>`;
    return;
  }

  dataCache.forEach(p => {
    const statusBadge = {
      active: '<span class="badge-status active">✓ Hiển thị</span>',
      draft: '<span class="badge-status draft">📝 Nháp</span>',
      hidden: '<span class="badge-status hidden">✗ Ẩn</span>'
    }[p.status] || '';

    const thumbHtml = p.thumbnail 
      ? `<div class="thumb-cell"><img src="${p.thumbnail}" alt="${p.name}" onerror="this.parentElement.innerHTML='<span class=text-muted>—</span>'"></div>` 
      : '<span class="text-muted text-xs">—</span>';

    const tr = document.createElement('tr');
    tr.className = 'table-row';
    tr.innerHTML = `
      <td><span class="badge-id">#${p.id}</span></td>
      <td>${thumbHtml}</td>
      <td>
        <div class="font-medium text-slate-800">${p.name}</div>
        <div class="text-xs text-muted font-mono mt-0.5">${p.sku || '—'}</div>
      </td>
      <td>
        <div class="price-text">${Number(p.price || 0).toLocaleString('vi-VN')}₫</div>
        ${p.sale_price ? `<div class="text-xs text-red-500 line-through">${Number(p.sale_price).toLocaleString('vi-VN')}₫</div>` : ''}
      </td>
      <td class="text-muted">${p.category?.name || '—'}</td>
      <td class="text-muted">${p.brand?.name || '—'}</td>
      <td>${statusBadge}</td>
      <td><span class="badge-id">${p.inventory?.stock ?? 0}</span></td>
      <td class="text-right">
        <button class="btn-edit" onclick='edit(${JSON.stringify(p).replace(/'/g, "&#39;")})'>
          <i class="fas fa-edit mr-1"></i>Sửa
        </button>
        <button class="btn-danger" style="margin-left:6px" onclick='del(${p.id})'>
          <i class="fas fa-trash mr-1"></i>Xoá
        </button>
      </td>`;
    tb.appendChild(tr);
  });
}

function openModal() { qs('#modal').classList.add('show'); }
function closeModal() { qs('#modal').classList.remove('show'); }

qs('#btnAdd').onclick = () => {
  qs('#form').reset();
  qs('#id').value = '';
  qs('#thumbnailPreview').innerHTML = '<span class="text-muted text-sm">Chưa có ảnh</span>';
  qs('#modalTitle').textContent = 'Thêm sản phẩm mới';
  openModal();
};

function edit(p) {
  qs('#modalTitle').textContent = 'Chỉnh sửa sản phẩm #' + p.id;
  openModal();
  Object.keys(p).forEach(k => {
    if (qs('#' + k)) qs('#' + k).value = p[k] ?? '';
  });
  if (p.category) qs('#category_id').value = p.category.id;
  if (p.brand) qs('#brand_id').value = p.brand.id;
  if (p.thumbnail) {
    qs('#thumbnail').value = p.thumbnail;
    qs('#thumbnailPreview').innerHTML = `<img src="${p.thumbnail}" alt="preview">`;
  } else {
    qs('#thumbnailPreview').innerHTML = '<span class="text-muted text-sm">Chưa có ảnh</span>';
  }
}

async function del(id) {
  if (!confirm('Bạn có chắc muốn xoá sản phẩm #' + id + '?')) return;
  const r = await fetch(API.delete(id), { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
  const j = await r.json();
  if (!r.ok || !j.ok) return toast(j, false);
  toast('Đã xoá sản phẩm');
  load();
}

qs('#form').onsubmit = async e => {
  e.preventDefault();
  let id = qs('#id').value;
  let data = {
    name: qs('#name').value,
    slug: qs('#slug').value || undefined,
    sku: qs('#sku').value,
    category_id: qs('#category_id').value || null,
    brand_id: qs('#brand_id').value || null,
    status: qs('#status').value,
    price: qs('#price').value || 0,
    sale_price: qs('#sale_price').value || null,
    engine_capacity: qs('#engine_capacity').value || null,
    fuel_type: qs('#fuel_type').value || null,
    transmission: qs('#transmission').value || null,
    power: qs('#power').value || null,
    weight: qs('#weight').value || null,
    color: qs('#color').value || null,
    description: qs('#description').value || null,
    thumbnail: qs('#thumbnail').value || null
  };
  let url = id ? API.update(id) : API.store;
  let method = id ? 'PUT' : 'POST';
  let r = await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    body: JSON.stringify(data)
  });
  let j = await r.json();
  if (!r.ok || !j.ok) return toast(j, false);
  toast(id ? 'Cập nhật sản phẩm thành công' : 'Thêm sản phẩm thành công');
  closeModal();
  load();
};

qs('#btnSearch').onclick = () => {
  let q = qs('#q').value.toLowerCase().trim();
  let status = qs('#filterStatus').value;
  let cat = qs('#filterCategory').value;
  let filtered = dataCache.filter(p => {
    let match = true;
    if (q) match = match && (p.name?.toLowerCase().includes(q) || p.sku?.toLowerCase().includes(q));
    if (status) match = match && p.status === status;
    if (cat) match = match && p.category_id == cat;
    return match;
  });
  dataCache = filtered;
  render();
  if (!q && !status && !cat) load();
};

qs('#modal').addEventListener('click', e => { if (e.target.id === 'modal') closeModal(); });

(async () => {
  await loadCategories();
  await loadBrands();
  await load();
  setupThumbnailPreview();
})();
</script>
@endpush