<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // JSON list + search
    public function list(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $perPage = (int)($request->get('per_page', 10));

        $cats = Category::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('name', 'like', "%$q%")
                   ->orWhere('slug', 'like', "%$q%");
            })
            ->with('parent:id,name')
            ->orderBy('sort_order')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $cats->items(),
            'meta' => [
                'current_page' => $cats->currentPage(),
                'per_page'     => $cats->perPage(),
                'total'        => $cats->total(),
                'last_page'    => $cats->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id'   => 'nullable|exists:categories,id',
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $cat = Category::create($data);
        return response()->json(['ok'=>true,'message'=>'Tạo danh mục thành công','data'=>$cat], 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'parent_id'   => ['nullable','different:id','exists:categories,id'],
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable','string','max:255', Rule::unique('categories','slug')->ignore($category->id)],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'sort_order'  => 'nullable|integer|min:0',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        // tránh tự tham chiếu
        if (isset($data['parent_id']) && (int)$data['parent_id'] === (int)$category->id) {
            return response()->json(['ok'=>false,'message'=>'parent_id không hợp lệ'], 422);
        }

        $category->update($data);
        return response()->json(['ok'=>true,'message'=>'Cập nhật danh mục thành công','data'=>$category]);
    }

    public function destroy(Category $category)
    {
        // Nếu muốn chặn xoá khi có sản phẩm, bạn có thể kiểm tra $category->products()->exists()
        $category->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá danh mục']);
    }
}
