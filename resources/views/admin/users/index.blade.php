@extends('layouts.admin')
@section('title','Người dùng')
@section('breadcrumb') <h2>Quản lý người dùng</h2> @endsection

@section('content')
<div class="card">
  <form id="formCreate" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px">
    @csrf
    <input name="name"  placeholder="Tên" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Mật khẩu (>=6)" required>
    <select name="role">
      <option value="user">user</option>
      <option value="admin">admin</option>
    </select>
    <button class="btn" type="submit">+ Thêm</button>
  </form>

  <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
    <input id="q" placeholder="Tìm theo tên/email">
    <button class="btn" id="btnSearch">Tìm</button>
  </div>

  <table id="tb">
    <thead><tr><th>#</th><th>Tên</th><th>Email</th><th>Role</th><th></th></tr></thead>
    <tbody></tbody>
  </table>

  <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
    <div id="meta" style="color:#64748b">—</div>
    <div style="display:flex;gap:8px">
      <button class="btn" id="prev">← Trước</button>
      <button class="btn" id="next">Sau →</button>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const API = {
  list: "{{ route('admin.users.list') }}",
  store: "{{ route('admin.users.store') }}",
  update: id => "{{ route('admin.users.update',':id') }}".replace(':id', id),
  destroy: id => "{{ route('admin.users.destroy',':id') }}".replace(':id', id),
};
const token = "{{ csrf_token() }}";
let current=1,last=1;

function tr(u){
  return `<tr>
    <td>${u.id}</td>
    <td><input value="${u.name||''}" data-id="${u.id}" data-field="name" onblur="save(this)"></td>
    <td><input value="${u.email||''}" data-id="${u.id}" data-field="email" onblur="save(this)"></td>
    <td>
      <select data-id="${u.id}" data-field="role" onchange="save(this)">
        ${['user','admin'].map(r=>`<option value="${r}" ${u.role===r?'selected':''}>${r}</option>`).join('')}
      </select>
    </td>
    <td style="text-align:right">
      <button class="btn danger" onclick="del(${u.id})">Xoá</button>
    </td>
  </tr>`;
}
async function fetchList(page=1){
  const url = new URL(API.list, location.origin);
  const q=document.getElementById('q').value.trim();
  if(q) url.searchParams.set('q',q);
  url.searchParams.set('page',page);
  url.searchParams.set('per_page',12);
  const j = await fetch(url,{headers:{Accept:'application/json'}}).then(r=>r.json());
  if(!j.ok){ showToast(j.message,false); return; }
  const tb=document.querySelector('#tb tbody'); tb.innerHTML='';
  (j.data||[]).forEach(u=>tb.insertAdjacentHTML('beforeend',tr(u)));
  current=j.meta.current_page; last=j.meta.last_page;
  document.getElementById('meta').textContent=`Trang ${current}/${last} • ${j.meta.total} người dùng`;
}
async function save(el){
  const id=el.dataset.id; const field=el.dataset.field; const value=el.value.trim();
  const payload={}; payload[field]=value;
  const res = await fetch(API.update(id),{
    method:'PUT', headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':token},
    body: JSON.stringify(payload)
  });
  const j=await res.json(); showToast(j.message|| (res.ok?'OK':'Lỗi'), res.ok && j.ok!==false);
}
async function del(id){
  if(!confirm('Xoá người dùng này?')) return;
  const res= await fetch(API.destroy(id),{method:'DELETE',headers:{'Accept':'application/json','X-CSRF-TOKEN':token}});
  const j=await res.json(); showToast(j.message|| (res.ok?'OK':'Lỗi'), res.ok && j.ok!==false);
  fetchList(current);
}
document.getElementById('formCreate').addEventListener('submit', async e=>{
  e.preventDefault();
  const fd=new FormData(e.target);
  const res= await fetch(API.store,{method:'POST',headers:{'Accept':'application/json','X-CSRF-TOKEN':token}, body:fd});
  const j=await res.json(); showToast(j.message|| (res.ok?'OK':'Lỗi'), res.ok && j.ok!==false);
  if(res.ok && j.ok) { e.target.reset(); fetchList(1); }
});
document.getElementById('btnSearch').onclick = ()=> fetchList(1);
document.getElementById('prev').onclick = ()=> { if(current>1) fetchList(current-1); }
document.getElementById('next').onclick = ()=> { if(current<last) fetchList(current+1); }
fetchList(1);
</script>
@endpush
