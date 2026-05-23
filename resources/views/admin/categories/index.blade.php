@extends('layouts.admin')

@section('title','Danh mục')
@section('breadcrumb')
    <nav class="flex items-center gap-2 text-sm">
        <span class="text-slate-400"><i class="fas fa-home"></i></span>
        <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
        <span class="text-slate-600 font-medium">Quản lý danh mục</span>
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
    .search-input {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        width: 100%;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }
    .search-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .form-input, .form-select, .form-textarea {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 10px;
        width: 100%;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        background: #fff;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .form-textarea {
        resize: vertical;
        min-height: 80px;
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
    .btn-primary:hover {
        background: #4f46e5;
    }
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
    .btn-ghost:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
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
    .btn-danger:hover {
        background: #fee2e2;
    }
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
    .btn-edit:hover {
        background: #e0e7ff;
    }
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
        padding: 14px 12px;
        font-size: 14px;
    }
    .table-row td:first-child {
        border-radius: 10px 0 0 10px;
    }
    .table-row td:last-child {
        border-radius: 0 10px 10px 10px;
    }
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
    .modal-overlay.show {
        display: flex;
    }
    .modal-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        padding: 24px;
        width: min(700px, 96vw);
    }
    .modal-panel h3 {
        margin: 0 0 16px;
        color: #1e293b;
        font-size: 18px;
        font-weight: 600;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    .form-grid label {
        font-size: 14px;
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
    .toast-notification.show {
        opacity: 1;
        transform: translateY(0);
    }
    .muted {
        color: #64748b;
        font-size: 14px;
    }
    .badge-id {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-active {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .badge-active.yes {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }
    .badge-active.no {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .text-right {
        text-align: right;
    }
</style>
@endpush

@section('content')
<div class="card-brand">
  <div class="toolbar">
    <input id="q" class="search-input" placeholder="Tìm theo tên/slug…" style="flex:1">
    <button class="btn-ghost" id="btnSearch">
      <i class="fas fa-search mr-1.5"></i>Tìm
    </button>
    <button class="btn-primary" id="btnAdd">
      <i class="fas fa-plus mr-1.5"></i>Thêm danh mục
    </button>
  </div>
  <div style="overflow:auto">
    <table class="table-brand">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Slug</th>
          <th>Kích hoạt</th>
          <th>Thứ tự</th>
          <th>Parent</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>
  <div class="toolbar" style="justify-content:space-between; margin-top: 16px; margin-bottom: 0;">
    <div id="meta" class="muted">—</div>
    <div style="display: flex; gap: 8px;">
      <button class="btn-ghost" id="prevBtn">← Trước</button>
      <button class="btn-ghost" id="nextBtn">Sau →</button>
    </div>
  </div>
</div>

<div class="modal-overlay" id="modal">
  <div class="modal-panel">
    <h3 id="modalTitle">Thêm danh mục</h3>
    <form id="form" class="form-grid">
      <input type="hidden" id="id">
      <label>Tên <span style="color:#ef4444">*</span><input id="name" class="form-input" required></label>
      <label>Slug<input id="slug" class="form-input" placeholder="bỏ trống sẽ tự tạo"></label>
      <label>Parent<select id="parent_id" class="form-select"><option value="">(Không)</option></select></label>
      <label>Kích hoạt<select id="is_active" class="form-select"><option value="1">Có</option><option value="0">Không</option></select></label>
      <label>Thứ tự<input id="sort_order" class="form-input" type="number" value="0"></label>
      <label style="grid-column:1/-1">Mô tả<textarea id="description" class="form-textarea" rows="3"></textarea></label>
      <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end; margin-top: 8px;">
        <button class="btn-ghost" type="button" id="btnClose">Đóng</button>
        <button class="btn-primary" type="submit">Lưu</button>
      </div>
    </form>
  </div>
</div>

<div id="toast" class="toast-notification"><span id="toastText">...</span></div>
@endsection

@push('scripts')
<script>
const API = {
  list: "{{ route('admin.categories.list') }}",
  store: "{{ route('admin.categories.store') }}",
  update: id=>"{{ route('admin.categories.update', ':id') }}".replace(':id', id),
  destroy: id=>"{{ route('admin.categories.destroy', ':id') }}".replace(':id', id),
};
const token='{{ csrf_token() }}', qs=s=>document.querySelector(s);
let currentPage=1,lastPage=1;
const toast=(m,ok=true)=>{const t=qs('#toast');t.style.background=ok?'#1e293b':'#ef4444';qs('#toastText').textContent=m?.message||m||'OK';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)};

async function loadParents(){
  const j=await fetch(API.list+'?per_page=200',{headers:{Accept:'application/json'}}).then(r=>r.json());
  const sel=qs('#parent_id'); sel.innerHTML='<option value="">(Không)</option>'; (j.data||[]).forEach(c=> sel.insertAdjacentHTML('beforeend',`<option value="${c.id}">${c.name}</option>`));
}
async function fetchList(page=1){
  const url=new URL(API.list,location.origin); const q=qs('#q').value.trim(); if(q) url.searchParams.set('q',q); url.searchParams.set('page',page);
  const j=await fetch(url,{headers:{Accept:'application/json'}}).then(r=>r.json()); if(!j.ok) return toast(j,false);
  const tb=qs('#tbody'); tb.innerHTML=''; 
  if(!j.data||j.data.length===0){
    tb.innerHTML=`<tr><td colspan="7" class="text-center py-12 text-slate-400"><i class="fas fa-inbox text-3xl mb-2 block"></i>Không tìm thấy danh mục nào</td></tr>`;
  } else {
    j.data.forEach(c=>{
      const tr=document.createElement('tr'); tr.className='table-row';
      tr.innerHTML=`<td><span class="badge-id">#${c.id}</span></td>
      <td><b>${c.name}</b></td>
      <td>${c.slug||'—'}</td>
      <td><span class="badge-active ${c.is_active?'yes':'no'}">${c.is_active?'✓ Có':'✗ Không'}</span></td>
      <td>${c.sort_order??0}</td>
      <td class="muted">${c.parent?.name??'(—)'}</td>
      <td class="text-right">
        <button class="btn-edit" onclick='edit(${JSON.stringify(c).replace(/'/g, "&#39;")})'>Sửa</button>
        <button class="btn-danger" style="margin-left:6px" onclick='del(${c.id})'>Xoá</button>
      </td>`;
      tb.appendChild(tr);
    });
  }
  currentPage=j.meta.current_page; lastPage=j.meta.last_page; qs('#meta').textContent=`Trang ${currentPage}/${lastPage} • Tổng ${j.meta.total}`;
}
function openModal(t){qs('#modalTitle').textContent=t;qs('#modal').classList.add('show')} function closeModal(){qs('#modal').classList.remove('show')}
qs('#btnClose').onclick=closeModal; qs('#modal').addEventListener('click',e=>{if(e.target.id==='modal') closeModal()});
qs('#btnSearch').onclick=()=>fetchList(1); qs('#prevBtn').onclick=()=>{if(currentPage>1) fetchList(currentPage-1)}; qs('#nextBtn').onclick=()=>{if(currentPage<lastPage) fetchList(currentPage+1)};
qs('#btnAdd').onclick=async()=>{qs('#id').value='';qs('#form').reset();await loadParents();openModal('Thêm danh mục')};
function edit(c){openModal('Sửa danh mục #'+c.id); loadParents().then(()=>{['id','name','slug','description','sort_order'].forEach(k=>qs('#'+k).value=c[k]??''); qs('#is_active').value=c.is_active?1:0; qs('#parent_id').value=c.parent_id??''})}
async function del(id){ if(!confirm('Xoá danh mục #'+id+'?')) return; const r=await fetch(API.destroy(id),{method:'DELETE',headers:{Accept:'application/json','X-CSRF-TOKEN':token}}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Đã xoá'); if(qs('#tbody').children.length===1&&currentPage>1) currentPage--; fetchList(currentPage);}
qs('#form').addEventListener('submit',async e=>{
  e.preventDefault(); const id=qs('#id').value.trim();
  const payload={parent_id:qs('#parent_id').value||null,name:qs('#name').value.trim(),slug:qs('#slug').value.trim()||undefined,description:qs('#description').value||null,is_active:qs('#is_active').value==='1',sort_order:qs('#sort_order').value||0};
  const url=id?API.update(id):API.store; const method=id?'PUT':'POST';
  const r=await fetch(url,{method,headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(payload)}); const j=await r.json();
  if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Lưu thành công'); closeModal(); fetchList(currentPage);
});
(function init(){ fetchList(1); })();
</script>
@endpush