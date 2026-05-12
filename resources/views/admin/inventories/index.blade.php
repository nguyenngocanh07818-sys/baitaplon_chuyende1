@extends('layouts.admin')

@section('title','Tồn kho')
@section('breadcrumb') <h2>Quản lý tồn kho</h2> @endsection

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
.toast{position:fixed;right:16px;top:76px;background:#8b1c49;color:#fff;padding:12px 14px;border-radius:14px;box-shadow:0 10px 20px rgba(255,126,184,.25);opacity:0;transform:translateY(-8px);transition:.25s;z-index:50}
.toast.show{opacity:1;transform:none}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.15);display:none;align-items:center;justify-content:center;padding:16px}
.modal.show{display:flex}.panel{background:#fff;border-radius:18px;box-shadow:0 10px 20px rgba(255,126,184,.25);padding:16px;width:min(760px,96vw)}
.qty{display:inline-flex;gap:6px;align-items:center}
</style>
@endpush

@section('content')
<div class="card">
  <div class="toolbar">
    <input id="q" placeholder="Tìm theo tên/sku…" style="flex:1">
    <label class="muted" style="display:flex;gap:6px;align-items:center"><input id="low_only" type="checkbox"> Chỉ cảnh báo (≤ ngưỡng)</label>
    <button class="btn ghost" id="btnSearch">Tìm</button>
    <button class="btn hk" id="btnAdd">+ Tạo tồn kho</button>
  </div>
  <div style="overflow:auto">
    <table class="table"><thead><tr><th>ID</th><th>Sản phẩm</th><th>Stock</th><th>Ngưỡng</th><th></th></tr></thead><tbody id="tbody"></tbody></table>
  </div>
  <div class="toolbar" style="justify-content:space-between">
    <div id="meta" class="muted">—</div>
    <div><button class="btn ghost" id="prevBtn">← Trước</button><button class="btn ghost" id="nextBtn">Sau →</button></div>
  </div>
</div>

<div class="modal" id="modal">
  <div class="panel">
    <h3 style="margin:0 0 12px;color:#8b1c49">Tạo tồn kho</h3>
    <form id="form" class="grid" style="grid-template-columns:repeat(2,minmax(0,1fr));gap:10px">
      <label>Sản phẩm<select id="product_id" required></select></label>
      <label>Stock ban đầu<input id="stock" type="number" value="0"></label>
      <label>Ngưỡng cảnh báo<input id="low_stock_threshold" type="number" value="5"></label>
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
  list: "{{ route('admin.inventories.list') }}",
  store: "{{ route('admin.inventories.store') }}",
  update: id=>"{{ route('admin.inventories.update', ':id') }}".replace(':id', id),
  destroy: id=>"{{ route('admin.inventories.destroy', ':id') }}".replace(':id', id),
  setStock: id=>"{{ route('admin.inventories.setStock', ':id') }}".replace(':id', id),
  adjust: id=>"{{ route('admin.inventories.adjustStock', ':id') }}".replace(':id', id),
  listProducts: "{{ route('admin.products.list') }}",
};
const token='{{ csrf_token() }}', qs=s=>document.querySelector(s);
let currentPage=1,lastPage=1; const toast=(m,ok=true)=>{const t=qs('#toast');t.style.background=ok?'#8b1c49':'#ff6b8a';qs('#toastText').textContent=m?.message||m||'OK';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)};

async function fetchList(page=1){
  const url=new URL(API.list,location.origin);
  const q=qs('#q').value.trim(); if(q) url.searchParams.set('q',q);
  if(qs('#low_only').checked) url.searchParams.set('low_only',1);
  url.searchParams.set('page',page);
  const j=await fetch(url,{headers:{Accept:'application/json'}}).then(r=>r.json()); if(!j.ok) return toast(j,false);
  const tb=qs('#tbody'); tb.innerHTML='';
  (j.data||[]).forEach(i=>{
    const tr=document.createElement('tr'); tr.className='row';
    tr.innerHTML=`<td>#${i.id}</td><td><b>${i.product?.name||''}</b><div class="muted">${i.product?.sku||''}</div></td>
      <td>
        <div class="qty">
          <button class="btn ghost" onclick="adjust(${i.id},-1)">–</button>
          <b id="stock-${i.id}">${i.stock}</b>
          <button class="btn ghost" onclick="adjust(${i.id},1)">+</button>
          <button class="btn ghost" onclick="setStock(${i.id})">Set</button>
        </div>
      </td>
      <td><input style="width:110px" type="number" value="${i.low_stock_threshold}" onchange="saveThreshold(${i.id}, this.value)"></td>
      <td style="text-align:right"><button class="btn danger" onclick='del(${i.id})'>Xoá</button></td>`;
    tb.appendChild(tr);
  });
  currentPage=j.meta.current_page; lastPage=j.meta.last_page; qs('#meta').textContent=`Trang ${currentPage}/${lastPage} • Tổng ${j.meta.total}`;
}
async function loadProducts(){
  const j=await fetch(API.listProducts+'?per_page=200',{headers:{Accept:'application/json'}}).then(r=>r.json());
  const sel=qs('#product_id'); sel.innerHTML=''; (j.data||[]).forEach(p=> sel.insertAdjacentHTML('beforeend',`<option value="${p.id}">${p.name} (${p.sku})</option>`));
}
function openModal(){qs('#modal').classList.add('show')} function closeModal(){qs('#modal').classList.remove('show')}
qs('#btnClose').onclick=closeModal; qs('#modal').addEventListener('click',e=>{if(e.target.id==='modal') closeModal()});
qs('#btnSearch').onclick=()=>fetchList(1); qs('#prevBtn').onclick=()=>{if(currentPage>1) fetchList(currentPage-1)}; qs('#nextBtn').onclick=()=>{if(currentPage<lastPage) fetchList(currentPage+1)};
qs('#btnAdd').onclick=()=>{loadProducts();qs('#form').reset();openModal();}
qs('#form').addEventListener('submit', async e=>{
  e.preventDefault(); const payload={product_id:qs('#product_id').value,stock:qs('#stock').value||0,low_stock_threshold:qs('#low_stock_threshold').value||5};
  const r=await fetch(API.store,{method:'POST',headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:JSON.stringify(payload)}); const j=await r.json();
  if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Tạo tồn kho thành công'); closeModal(); fetchList(currentPage);
});
async function saveThreshold(id,thr){ const r=await fetch(API.update(id),{method:'PUT',headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify({stock:document.getElementById('stock-'+id).textContent,low_stock_threshold:thr})}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); toast('Đã cập nhật ngưỡng'); }
async function setStock(id){ const val=prompt('Nhập số lượng mới:'); if(val===null) return; const r=await fetch(API.setStock(id),{method:'PUT',headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify({stock:parseInt(val||0)})}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); document.getElementById('stock-'+id).textContent=j.data.stock; toast(j.message||'OK'); }
async function adjust(id,delta){ const r=await fetch(API.adjust(id),{method:'PUT',headers:{Accept:'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},body:JSON.stringify({delta})}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); document.getElementById('stock-'+id).textContent=j.data.stock; toast(j.message||'OK'); }
async function del(id){ if(!confirm('Xoá tồn kho #'+id+'?')) return; const r=await fetch(API.destroy(id),{method:'DELETE',headers:{Accept:'application/json','X-CSRF-TOKEN':token}}); const j=await r.json(); if(!r.ok||!j.ok) return toast(j,false); toast(j.message||'Đã xoá'); if(qs('#tbody').children.length===1&&currentPage>1) currentPage--; fetchList(currentPage); }
(function init(){ fetchList(1); })();
</script>
@endpush
