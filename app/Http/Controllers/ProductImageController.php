<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    // GET /admin/product-images/list?product_id=...
    public function list(Request $request)
    {
        $perPage   = (int)($request->get('per_page', 20));
        $productId = $request->get('product_id'); // thường sẽ filter theo sản phẩm
        $q         = trim((string)$request->get('q', ''));

        $imgs = ProductImage::query()
            ->when($productId, fn($qr) => $qr->where('product_id', $productId))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('path','like',"%$q%")
                   ->orWhere('alt','like',"%$q%");
            })
            ->with('product:id,name')
            ->orderBy('product_id')
            ->orderBy('sort_order')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $imgs->items(),
            'meta' => [
                'current_page' => $imgs->currentPage(),
                'per_page'     => $imgs->perPage(),
                'total'        => $imgs->total(),
                'last_page'    => $imgs->lastPage(),
            ],
        ]);
    }

    // POST /admin/product-images
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'path'       => 'required|string|max:1024', // URL hoặc local path (tự upload ngoài)
            'alt'        => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $img = ProductImage::create($data);
        return response()->json(['ok'=>true,'message'=>'Đã thêm ảnh sản phẩm','data'=>$img], 201);
    }

    // PUT /admin/product-images/{productImage}
    public function update(Request $request, ProductImage $productImage)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'path'       => 'required|string|max:1024',
            'alt'        => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $productImage->update($data);
        return response()->json(['ok'=>true,'message'=>'Đã cập nhật ảnh','data'=>$productImage]);
    }

    // DELETE /admin/product-images/{productImage}
    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá ảnh']);
    }
}
