@extends('layouts.admin')

@section('title','Ảnh sản phẩm')
@section('breadcrumb') <h2>Quản lý ảnh sản phẩm</h2> @endsection

@push('styles')
<style>
.card{background:#fff;border-radius:18px;box-shadow:0 10px 20px rgba(255,126,184,.25);padding:16px}
.toolbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-bottom:12px}
input,select{border:1px solid #ffc2dd;padding:10px 12px;border-radius:12px;width:100%}
.btn.hk{border:0;background:#ff7eb8;color:#fff;border-radius:12px;padding:10px 14px}
.btn.ghost{background:#fff0f6;color:#8b1c49;border:1px solid #ffc2dd;border-radius:12px;padding:10px 14px}
.btn.danger{background:#ff6b8a;color:#fff;border-radius:12px;padding:10px 14px}
.table{width:100%;border-collapse:separate;border-spacing:0 8px}
.table th{font-size:12px;text-transform:uppercase;color:#777;text-align:left;padding:6px 10px}
.row{background:#fff;border:1px solid #ffc2dd;border-radius:12px}.row td{padding:10px}
.thumb{width:80px;height:60px;object-fit:cover;border-radius:10px;border:1px solid #ffd1e2}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.15);display:none;align-items:center;justify-content:center;padding:16px}
.modal.show{display:flex}.panel{background:#fff;border-radius:18px;box-shadow:0 10px 20px rgba(255,126,184,.25);padding:16px;width:min(820px,96vw)}
.toast{position:fixed;right:16px;top:76px;background:#8b1c49;color:#fff;padding:12px 14px;border-radius:14px;box-shadow:0 10px 20px rgba(255,126,184,.25);opacity:0;transform:translateY(-8px);transition:.25s;z-index:50}
.toast.show{opacity:1;transform:none}
</style>
@endpush

@section('content')
<div class="card">
  <div class="toolbar">
    <select id="filterProduct" style="min-width:260px"><option value="">— Chọn sản phẩm —</option></select>
    <input id="q" placeholder="Tìm theo đường dẫn / alt…" style="flex:1">
    <button class="btn ghost" id="btnSearch">Tìm</button>
    <button class="btn hk" id="btnAdd">+ Thêm ảnh</button>
  </div>
  <div style="overflow:auto">
    <table class="table"><thead><tr><th>ID</th><th>Ảnh</th><th>Đường dẫn</th><th>ALT</th><th>SP</th><th>Thứ tự</th><th></th></tr></thead><tbody id="tbody"></tbody></table>
  </div>
  <div class="toolbar" style="justify-content:space-between">
    <div id="meta" class="muted">—</div>
    <div><button class="btn ghost" id="prevBtn">← Trước</button><button class="btn ghost" id="nextBtn">Sau →</button></div>
  </div>
</div>

<div class="modal" id="modal">
  <div class="panel">
    <h3 id="modalTitle" style="margin:0 0 12px;color:#8b1c49">Thêm ảnh</h3>
    <form id="form" class="grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:10px">
      <input type="hidden" id="id">
      <label>Sản phẩm<select id="product_id" required></select></label>
      <label>Thứ tự<input id="sort_order" type="number" value="0"></label>
      <label style="grid-column:1/-1">Đường dẫn (URL / path)<input id="path" required></label>
      <label style="grid-column:1/-1">ALT<input id="alt"></label>
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
  list: "{{ route('admin.productImages.list') }}",
  store: "{{ route('admin.productImages.store') }}",
  update: id=>"{{ route('admin.productImages.update', ':id') }}".replace(':id', id),
  destroy: id=>"{{ route('admin.productImages.destroy', ':id') }}".replace(':id', id),
  listProducts: "{{ route('admin.products.list') }}",
};
const token='{{ csrf_token() }}', qs=s=>document.querySelector(s);
let currentPage=1,lastPage=1; const toast=(m,ok=true)=>{const t=qs('#toast');t.style.background=ok?'#8b1c49':'#ff6b8a';qs('#toastText').textContent=m?.message||m||'OK';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)};

async function loadProducts(){
  const j=await fetch(API.listProducts+'?per_page=200',{headers:{Accept:'application/json'}}).then(r=>r.json());
  const sel=qs('#filterProduct'), sel2=qs('#product_id'); sel.innerHTML='<option value="">— Chọn sản phẩm —</option>'; sel2.innerHTML='';
  (j.data||[]).forEach(p=>{ sel.insertAdjacentHTML('beforeend',`<option value="${p.id}">${p.name} (${p.sku})</option>`); sel2.insertAdjacentHTML('beforeend',`<option value="${p.id}">${p.name} (${p.sku})</option>`); });
}
async function fetchList(page=1){
  const url=new URL(API.list,location.origin);
  const pid=qs('#filterProduct').value; const q=qs('#q').value.trim();
  if(pid) url.searchParams.set('product_id',pid); if(q) url.searchParams.set('q',q); url.searchParams.set('page',page);
  const j=await fetch(url,{headers:{Accept:'application/json'}}).then(r=>r.json()); if(!j.ok) return toast(j,false);
  const tb=qs('#tbody'); tb.innerHTML='';
  (j.data||[]).forEach(im=>{
    const tr=document.createElement('tr'); tr.className='row';
    tr.innerHTML=`<td>#${im.id}</td><td><img class="thumb" src="${im.path}" onerror="this.src='';this.alt='(no preview)'"></td>
      <td>${im.path}</td><td>${im.alt||''}</td><td>${im.product?.name||''}</td><td>${im.sort_order??0}</td>
      <td style="text-align:right"><button class="btn ghost" onclick='edit(${JSON.stringify(im)})'>Sửa</button><button class="btn danger" onclick='del(${im.id})'>Xoá</button></td>`;
    tb.appendChild(tr);
  });
  currentPage=j.meta.current_page; lastPage=j.meta.last_page; qs('#meta').textContent=`Trang ${currentPage}/${lastPage} • Tổng ${j.meta.total}`;
}
function openModal(t){qs('#modalTitle').textContent=t;qs('#modal').classList.add('show')} function closeModal(){qs('#modal').classList.remove('show')}
qs('#btnClose').onclick=closeModal; qs('#modal').addEventListener('click',e=>{if(e.target.id==='modal') closeModal()});
qs('#btnSearch').onclick=()=>fetchList(1); qs('#prevBtn').onclick=()=>{if(currentPage>1) fetchList(currentPage-1)}; qs('#nextBtn').onclick=()=>{if(currentPage<lastPage) fetchList(currentPage+1)};
qs('#btnAdd').onclick=()=>{qs('#id').value='';qs('#form').reset();openModal('Thêm ảnh')};
function edit(im){openModal('Sửa ảnh #'+im.id); ['id','product_id','path','alt','sort_order'].forEach(k=>qs('#'+k).value=im[k]??'')}
async function del(id){ if(!confirm('Xoá ảnh #'+id+'?')) return; const r=await fetch(API.destroy(id),{method:'DELETE',headers:{Accept:'application/json','X-CSRF-TOKEN':token}}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Đã xoá'); if(qs('#tbody').children.length===1&&currentPage>1) currentPage--; fetchList(currentPage); }
qs('#form').addEventListener('submit',async e=>{
  e.preventDefault(); const id=qs('#id').value.trim();
  const payload={product_id:qs('#product_id').value,path:qs('#path').value.trim(),alt:qs('#alt').value||null,sort_order:qs('#sort_order').value||0};
  const url=id?API.update(id):API.store; const method=id?'PUT':'POST';
  const r=await fetch(url,{method,headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify(payload)}); const j=await r.json();
  if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Lưu thành công'); closeModal(); fetchList(currentPage);
});
(function init(){ loadProducts().then(()=>fetchList(1)); })();
</script>
@endpush
