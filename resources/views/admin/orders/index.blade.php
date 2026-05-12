@extends('layouts.admin')

@section('title','Đơn hàng')

@push('styles')
<style>
.card{background:#fff;border-radius:16px;padding:16px;box-shadow:0 5px 15px rgba(0,0,0,.08)}
.toolbar{display:flex;gap:8px;margin-bottom:10px}
input,select{border:1px solid #ddd;padding:8px;border-radius:8px}
.btn{padding:6px 10px;border-radius:8px;border:0;cursor:pointer}
.btn.gray{background:#eee}
.badge{padding:3px 8px;border-radius:999px;font-size:12px}
.pending{background:#fef3c7}
.processing{background:#dbeafe}
.paid{background:#dcfce7}
.shipped{background:#ede9fe}
.completed{background:#ccfbf1}
.cancelled{background:#fee2e2}
.refunded{background:#e5e7eb}
.toast{position:fixed;top:20px;right:20px;background:#111;color:#fff;padding:10px;border-radius:8px;opacity:0;transition:.3s}
.toast.show{opacity:1}
</style>
@endpush

@section('content')
<div class="card">

<div class="toolbar">
  <input id="q" placeholder="Tìm đơn...">
  <select id="perPage">
    <option value="10">10</option>
    <option value="20">20</option>
  </select>
</div>

<table width="100%">
<thead>
<tr>
<th>ID</th>
<th>Khách</th>
<th>Tổng</th>
<th>Trạng thái</th>
<th>Ngày</th>
<th></th>
</tr>
</thead>
<tbody id="tbody"></tbody>
</table>

<div class="toolbar" style="justify-content:space-between">
  <div id="meta"></div>
  <div>
    <button class="btn gray" id="prev">←</button>
    <button class="btn gray" id="next">→</button>
  </div>
</div>

</div>

<div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
const API={
 list:"{{ route('admin.orders.list') }}",
 update:id=>"{{ route('admin.orders.update',':id') }}".replace(':id',id),
 delete:id=>"{{ route('admin.orders.destroy',':id') }}".replace(':id',id)
};
const token='{{ csrf_token() }}';

const qs=s=>document.querySelector(s);

let page=1,last=1,timeout=null;

function toast(msg){
 let t=qs('#toast');
 t.innerText=msg;
 t.classList.add('show');
 setTimeout(()=>t.classList.remove('show'),2000);
}

async function fetchJSON(url,opts={}){
 let r=await fetch(url,{
   ...opts,
   headers:{
     'Accept':'application/json',
     ...(opts.headers||{})
   }
 });
 let text=await r.text();
 try{return JSON.parse(text)}catch{
   console.error(text);
   toast('Server lỗi');
   throw new Error();
 }
}

async function load(p=1){
 page=p;
 let url=new URL(API.list,location.origin);
 url.searchParams.set('page',page);
 url.searchParams.set('per_page',qs('#perPage').value);
 let q=qs('#q').value.trim();
 if(q) url.searchParams.set('q',q);

 let j=await fetchJSON(url);
 if(!j.ok) return toast('Lỗi load');

 let tb=qs('#tbody'); tb.innerHTML='';

 j.data.forEach(o=>{
  tb.innerHTML+=`
  <tr>
    <td>#${o.id}</td>
    <td>${o.customer_name||''}</td>
    <td>${Number(o.total||0).toLocaleString()}₫</td>
    <td><span class="badge ${o.status}">${o.status}</span></td>
    <td>${new Date(o.created_at).toLocaleString('vi-VN')}</td>
    <td>
      <select onchange="action(${o.id},this.value)">
        <option value="">--</option>
        ${actions(o.status)}
        <option value="delete">Xoá</option>
      </select>
    </td>
  </tr>`;
 });

 last=j.meta.last_page;
 qs('#meta').innerText=`Trang ${j.meta.current_page}/${last} - ${j.meta.total} đơn`;
}

function actions(s){
 const map={
  pending:['processing','cancelled'],
  processing:['paid','cancelled'],
  paid:['shipped','refunded'],
  shipped:['completed']
 };
 return (map[s]||[]).map(x=>`<option value="${x}">${x}</option>`).join('');
}

async function action(id,val){
 if(!val) return;

 if(val==='delete'){
  if(!confirm('Xoá?')) return;
  let j=await fetchJSON(API.delete(id),{
    method:'DELETE',
    headers:{'X-CSRF-TOKEN':token}
  });
  toast(j.message);
  return load(page);
 }

 if(!confirm('Đổi trạng thái?')) return;

 let j=await fetchJSON(API.update(id),{
  method:'PUT',
  headers:{
    'Content-Type':'application/json',
    'X-CSRF-TOKEN':token
  },
  body:JSON.stringify({status:val})
 });

 toast(j.message);
 load(page);
}

qs('#q').oninput=()=>{
 clearTimeout(timeout);
 timeout=setTimeout(()=>load(1),500);
};

qs('#perPage').onchange=()=>load(1);
qs('#prev').onclick=()=>page>1&&load(page-1);
qs('#next').onclick=()=>page<last&&load(page+1);

load();
</script>
@endpush