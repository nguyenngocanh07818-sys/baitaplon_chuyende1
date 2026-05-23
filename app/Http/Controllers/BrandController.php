<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function list(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $perPage = (int)($request->get('per_page', 10));

        $brands = Brand::query()
            ->when($q !== '', fn($qr) =>
                $qr->where('name','like',"%$q%")
                   ->orWhere('slug','like',"%$q%")
                   ->orWhere('country','like',"%$q%")
            )
            ->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'ok'=>true,
            'data'=>$brands->items(),
            'meta'=>[
                'current_page'=>$brands->currentPage(),
                'per_page'=>$brands->perPage(),
                'total'=>$brands->total(),
                'last_page'=>$brands->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:brands,slug',
            'country'     => 'nullable|string|max:120',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $brand = Brand::create($data);
        return response()->json(['ok'=>true,'message'=>'Tạo thương hiệu thành công','data'=>$brand], 201);
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable','string','max:255', Rule::unique('brands','slug')->ignore($brand->id)],
            'country'     => 'nullable|string|max:120',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $brand->update($data);
        return response()->json(['ok'=>true,'message'=>'Cập nhật thương hiệu thành công','data'=>$brand]);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá thương hiệu']);
    }
}
