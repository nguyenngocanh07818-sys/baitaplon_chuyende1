<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function list(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $perPage = (int)($request->get('per_page', 10));
        $categoryId = $request->get('category_id');
        $brandId = $request->get('brand_id');
        $status = $request->get('status');

        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $products = Product::query()
            ->with(['category:id,name','brand:id,name','inventory:product_id,stock'])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('name','like',"%$q%")
                   ->orWhere('sku','like',"%$q%")
                   ->orWhere('slug','like',"%$q%");
            })
            ->when($categoryId, fn($qr)=> $qr->where('category_id',$categoryId))
            ->when($brandId, fn($qr)=> $qr->where('brand_id',$brandId))
            ->when($status, fn($qr)=> $qr->where('status',$status))
            ->when($minPrice, fn($qr)=> $qr->where('price','>=',$minPrice))
            ->when($maxPrice, fn($qr)=> $qr->where('price','<=',$maxPrice))
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'ok'=>true,
            'data'=>$products->items(),
            'meta'=>[
                'current_page'=>$products->currentPage(),
                'per_page'=>$products->perPage(),
                'total'=>$products->total(),
                'last_page'=>$products->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'sku'         => 'required|string|max:64|unique:products,sku',
            'description' => 'nullable|string',

            // 🚗 XE MÁY
            'engine_capacity' => 'nullable|integer|min:50|max:2000',
            'fuel_type'       => ['nullable', Rule::in(['gasoline','electric'])],
            'transmission'    => ['nullable', Rule::in(['manual','automatic'])],
            'power'           => 'nullable|string|max:100',
            'weight'          => 'nullable|integer|min:50|max:500',
            'color'           => 'nullable|string|max:50',

            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lte:price',

            'thumbnail'   => 'nullable|string',
            'is_featured' => 'boolean',
            'status'      => ['required', Rule::in(['draft','active','hidden'])],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $product = DB::transaction(function () use ($data) {
            $p = Product::create($data);
            $p->inventory()->create(['stock'=>0]);
            return $p->load('inventory');
        });

        return response()->json(['ok'=>true,'message'=>'Tạo sản phẩm thành công','data'=>$product], 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable','string','max:255', Rule::unique('products','slug')->ignore($product->id)],
            'sku'         => ['required','string','max:64', Rule::unique('products','sku')->ignore($product->id)],
            'description' => 'nullable|string',

            // 🚗 XE MÁY
            'engine_capacity' => 'nullable|integer|min:50|max:2000',
            'fuel_type'       => ['nullable', Rule::in(['gasoline','electric'])],
            'transmission'    => ['nullable', Rule::in(['manual','automatic'])],
            'power'           => 'nullable|string|max:100',
            'weight'          => 'nullable|integer|min:50|max:500',
            'color'           => 'nullable|string|max:50',

            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0|lte:price',

            'thumbnail'   => 'nullable|string',
            'is_featured' => 'boolean',
            'status'      => ['required', Rule::in(['draft','active','hidden'])],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $product->update($data);
        $product->load('inventory');

        return response()->json(['ok'=>true,'message'=>'Cập nhật sản phẩm thành công','data'=>$product]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá sản phẩm']);
    }
}