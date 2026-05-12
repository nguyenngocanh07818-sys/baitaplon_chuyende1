@extends('layouts.admin')

@section('title','Danh mục')
@section('breadcrumb') <h2>Quản lý danh mục</h2> @endsection

@push('styles')
<style>
.card{background:#fff;border-radius:18px;box-shadow:0 10px 20px rgba(255,126,184,.25);padding:16px}
.toolbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:12px}
input,select,textarea{border:1px solid #ffc2dd;padding:10px 12px;border-radius:12px;width:100%}
.btn.hk{border:0;background:#ff7eb8;color:#fff;border-radius:12px;padding:10px 14px}
.btn.ghost{background:#fff0f6;color:#8b1c49;border:1px solid #ffc2dd;border-radius:12px;padding:10px 14px}
.btn.danger{background:#ff6b8a;color:#fff;border-radius:12px;padding:10px 14px}
.table{width:100%;border-collapse:separate;border-spacing:0 8px}
.table th{font-size:12px;text-transform:uppercase;color:#777;text-align:left;padding:6px 10px}
.row{background:#fff;border:1px solid #ffc2dd;border-radius:12px}
.row td{padding:10px}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.15);display:none;align-items:center;justify-content:center;padding:16px}
.modal.show{display:flex}
.panel{background:#fff;border-radius:18px;box-shadow:0 10px 20px rgba(255,126,184,.25);padding:16px;width:min(760px,96vw)}
.toast{position:fixed;right:16px;top:76px;background:#8b1c49;color:#fff;padding:12px 14px;border-radius:14px;box-shadow:0 10px 20px rgba(255,126,184,.25);opacity:0;transform:translateY(-8px);transition:.25s;z-index:50}
.toast.show{opacity:1;transform:none}
</style>
@endpush

@section('content')
<div class="card">
  <div class="toolbar">
    <input id="q" placeholder="Tìm theo tên/slug…" style="flex:1">
    <button class="btn ghost" id="btnSearch">Tìm</button>
    <button class="btn hk" id="btnAdd">+ Thêm danh mục</button>
  </div>
  <div style="overflow:auto">
    <table class="table">
      <thead><tr><th>ID</th><th>Tên</th><th>Slug</th><th>Kích hoạt</th><th>Thứ tự</th><th>Parent</th><th></th></tr></thead>
      <tbody id="tbody"></tbody>
    </table>
  </div>
  <div class="toolbar" style="justify-content:space-between">
    <div id="meta" class="muted">—</div>
    <div><button class="btn ghost" id="prevBtn">← Trước</button><button class="btn ghost" id="nextBtn">Sau →</button></div>
  </div>
</div>

<div class="modal" id="modal">
  <div class="panel">
    <h3 id="modalTitle" style="margin:0 0 12px;color:#8b1c49">Thêm danh mục</h3>
    <form id="form" class="grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:10px">
      <input type="hidden" id="id">
      <label>Tên<input id="name" required></label>
      <label>Slug<input id="slug" placeholder="bỏ trống sẽ tự tạo"></label>
      <label>Parent<select id="parent_id"><option value="">(Không)</option></select></label>
      <label>Kích hoạt<select id="is_active"><option value="1">Có</option><option value="0">Không</option></select></label>
      <label>Thứ tự<input id="sort_order" type="number" value="0"></label>
      <label style="grid-column:1/-1">Mô tả<textarea id="description"></textarea></label>
      <div style="grid-column:1/-1;display:flex;gap:8px;justify-content:flex-end">
        <button class="btn ghost" type="button" id="btnClose">Đóng</button>
        <button class="btn hk" type="submit">Lưu</button>
      </div>
    </form>
  </div>
</div>

<div id="toast" class="toast"><span id="toastText">...</span></div>
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
const toast=(m,ok=true)=>{const t=qs('#toast');t.style.background=ok?'#8b1c49':'#ff6b8a';qs('#toastText').textContent=m?.message||m||'OK';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)};

async function loadParents(){
  const j=await fetch(API.list+'?per_page=200',{headers:{Accept:'application/json'}}).then(r=>r.json());
  const sel=qs('#parent_id'); sel.innerHTML='<option value="">(Không)</option>'; (j.data||[]).forEach(c=> sel.insertAdjacentHTML('beforeend',`<option value="${c.id}">${c.name}</option>`));
}
async function fetchList(page=1){
  const url=new URL(API.list,location.origin); const q=qs('#q').value.trim(); if(q) url.searchParams.set('q',q); url.searchParams.set('page',page);
  const j=await fetch(url,{headers:{Accept:'application/json'}}).then(r=>r.json()); if(!j.ok) return toast(j,false);
  const tb=qs('#tbody'); tb.innerHTML=''; (j.data||[]).forEach(c=>{
    const tr=document.createElement('tr'); tr.className='row';
    tr.innerHTML=`<td>#${c.id}</td><td><b>${c.name}</b></td><td>${c.slug||''}</td><td>${c.is_active?'✓':'✗'}</td><td>${c.sort_order??0}</td><td>${c.parent?.name??'(—)'}</td>
    <td style="text-align:right"><button class="btn ghost" onclick='edit(${JSON.stringify(c)})'>Sửa</button><button class="btn danger" onclick='del(${c.id})'>Xoá</button></td>`;
    tb.appendChild(tr);
  });
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
