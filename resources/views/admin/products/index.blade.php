@extends('layouts.admin')

@section('title','Sản phẩm')
@section('breadcrumb') <h2>Quản lý sản phẩm</h2> @endsection

@push('styles')
<style>
.card{background:#fff;border-radius:16px;box-shadow:0 5px 15px rgba(0,0,0,.08);padding:16px}
.toolbar{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px}
input,select,textarea{border:1px solid #ddd;padding:8px 10px;border-radius:10px;width:100%}
.btn{border:0;border-radius:10px;padding:8px 12px;cursor:pointer}
.btn.primary{background:#4f46e5;color:#fff}
.btn.gray{background:#f3f4f6}
.btn.danger{background:#ef4444;color:#fff}
.table{width:100%;border-collapse:collapse}
.table th,.table td{padding:8px;border-bottom:1px solid #eee}
.badge{padding:3px 8px;border-radius:999px;background:#eee;font-size:12px}
.modal{position:fixed;inset:0;background:rgba(0,0,0,.3);display:none;align-items:center;justify-content:center}
.modal.show{display:flex}
.panel{background:#fff;padding:16px;border-radius:12px;width:900px;max-width:95%}
.toast{position:fixed;top:20px;right:20px;background:#111;color:#fff;padding:10px;border-radius:10px;opacity:0;transition:.3s}
.toast.show{opacity:1}
.grid{display:grid;gap:10px}
.grid-3{grid-template-columns:repeat(3,1fr)}
</style>
@endpush

@section('content')
<div class="card">

  <div class="toolbar">
    <input id="q" placeholder="Tìm sản phẩm...">

    <select id="filterStatus">
      <option value="">Trạng thái</option>
      <option value="active">Hiển thị</option>
      <option value="draft">Nháp</option>
      <option value="hidden">Ẩn</option>
    </select>

    <select id="filterCategory"></select>
    <button class="btn gray" id="btnSearch">Tìm</button>
    <button class="btn primary" id="btnAdd">+ Thêm</button>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Danh mục</th>
        <th>Thương hiệu</th>
        <th>Kho</th>
        <th></th>
      </tr>
    </thead>
    <tbody id="tbody"></tbody>
  </table>

</div>

{{-- Modal --}}
<div class="modal" id="modal">
  <div class="panel">
    <h3 id="modalTitle">Thêm / Sửa sản phẩm</h3>

    <form id="form" class="grid grid-3">
      <input type="hidden" id="id">

      <input id="name" placeholder="Tên" required>
      <input id="slug" placeholder="Slug">
      <input id="sku" placeholder="SKU" required>

      <select id="category_id"></select>
      <select id="brand_id"></select>

      <select id="status">
        <option value="active">Hiển thị</option>
        <option value="draft">Nháp</option>
        <option value="hidden">Ẩn</option>
      </select>

      <input id="price" type="number" placeholder="Giá">
      <input id="sale_price" type="number" placeholder="Giá giảm">

      {{-- XE MÁY --}}
      <input id="engine_capacity" placeholder="Dung tích (cc)">
      <select id="fuel_type">
        <option value="">Nhiên liệu</option>
        <option value="gasoline">Xăng</option>
        <option value="electric">Điện</option>
      </select>

      <select id="transmission">
        <option value="">Hộp số</option>
        <option value="manual">Số</option>
        <option value="automatic">Tự động</option>
      </select>

      <input id="power" placeholder="Công suất">
      <input id="weight" placeholder="Trọng lượng">
      <input id="color" placeholder="Màu sắc">

      <textarea id="description" placeholder="Mô tả"></textarea>

      <div style="grid-column:1/-1;text-align:right">
        <button type="button" class="btn gray" onclick="closeModal()">Huỷ</button>
        <button class="btn primary">Lưu</button>
      </div>
    </form>
  </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
const API={
 list:"{{ route('admin.products.list') }}",
 store:"{{ route('admin.products.store') }}",
 update:id=>"{{ route('admin.products.update',':id') }}".replace(':id',id),
 delete:id=>"{{ route('admin.products.destroy',':id') }}".replace(':id',id),
 categories:"{{ route('admin.categories.list') }}",
 brands:"{{ route('admin.brands.list') }}"
};

const qs=s=>document.querySelector(s);
const toast=(m)=>{
 const t=qs('#toast');
 t.innerText=m;
 t.classList.add('show');
 setTimeout(()=>t.classList.remove('show'),2000);
}

let dataCache=[];

// LOAD CATEGORY
async function loadCategories(){
 let r=await fetch(API.categories).then(r=>r.json());
 let html='<option value="">Danh mục</option>';
 r.data.forEach(c=>html+=`<option value="${c.id}">${c.name}</option>`);
 qs('#filterCategory').innerHTML=html;
 qs('#category_id').innerHTML=html;
}

// LOAD BRAND
async function loadBrands(){
 let r=await fetch(API.brands).then(r=>r.json());
 let html='<option value="">Thương hiệu</option>';
 r.data.forEach(b=>html+=`<option value="${b.id}">${b.name}</option>`);
 qs('#brand_id').innerHTML=html;
}

// LOAD PRODUCT
async function load(){
 let r=await fetch(API.list).then(r=>r.json());
 dataCache=r.data||[];
 render();
}

// RENDER TABLE
function render(){
 let tb=qs('#tbody');
 tb.innerHTML='';

 dataCache.forEach(p=>{
  tb.innerHTML+=`
   <tr>
    <td>${p.id}</td>
    <td>${p.name}</td>
    <td>${Number(p.price||0).toLocaleString()}₫</td>
    <td>${p.category?.name||''}</td>
    <td>${p.brand?.name||''}</td>
    <td>${p.inventory?.stock||0}</td>
    <td>
      <button onclick='edit(${JSON.stringify(p)})'>Sửa</button>
      <button onclick='del(${p.id})'>Xoá</button>
    </td>
   </tr>
  `;
 });
}

// MODAL
function openModal(){qs('#modal').classList.add('show')}
function closeModal(){qs('#modal').classList.remove('show')}

// ADD
qs('#btnAdd').onclick=()=>{
 qs('#form').reset();
 qs('#id').value='';
 openModal();
}

// EDIT
function edit(p){
 openModal();

 Object.keys(p).forEach(k=>{
  if(qs('#'+k)) qs('#'+k).value=p[k]??'';
 });

 if(p.category) qs('#category_id').value=p.category.id;
 if(p.brand) qs('#brand_id').value=p.brand.id;
}

// DELETE
async function del(id){
 if(!confirm('Xoá?'))return;
 await fetch(API.delete(id),{method:'DELETE'});
 toast('Đã xoá');
 load();
}

// SAVE
qs('#form').onsubmit=async e=>{
 e.preventDefault();

 let id=qs('#id').value;

 let data={
  name:qs('#name').value,
  slug:qs('#slug').value||undefined,
  sku:qs('#sku').value,
  category_id:qs('#category_id').value,
  brand_id:qs('#brand_id').value,
  status:qs('#status').value,
  price:qs('#price').value||0,
  sale_price:qs('#sale_price').value||null,

  engine_capacity:qs('#engine_capacity').value||null,
  fuel_type:qs('#fuel_type').value||null,
  transmission:qs('#transmission').value||null,
  power:qs('#power').value||null,
  weight:qs('#weight').value||null,
  color:qs('#color').value||null,

  description:qs('#description').value||null
 };

 let url=id?API.update(id):API.store;
 let method=id?'PUT':'POST';

 let r=await fetch(url,{
  method,
  headers:{
   'Content-Type':'application/json',
   'X-CSRF-TOKEN':'{{ csrf_token() }}'
  },
  body:JSON.stringify(data)
 });

 let j=await r.json();

 if(!j.ok) return toast('Lỗi');
 toast('Thành công');
 closeModal();
 load();
}

// SEARCH
qs('#btnSearch').onclick=()=>{
 let q=qs('#q').value.toLowerCase();
 load(); // reset data trước
 setTimeout(()=>{
   dataCache=dataCache.filter(p=>p.name.toLowerCase().includes(q));
   render();
 },200);
}

// INIT
(async()=>{
 await loadCategories();
 await loadBrands();
 await load();
})();
</script>
@endpush