<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicProductController extends Controller
{
    private function ok($data = null, $meta = null, $message = null)
    {
        return response()->json([
            'ok'      => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => $meta,
        ]);
    }

    private function err($message = 'Có lỗi xảy ra', $code = 422)
    {
        return response()->json(['ok' => false, 'message' => $message], $code);
    }

    /**
     * GET /api/products
     * Params: q, category_id, brand_id, status, per_page, page
     */
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $categoryId = $request->get('category_id');
        $perPage = (int)($request->get('per_page', 10));

        $products = Product::query()
            ->with(['category', 'brand', 'inventory', 'reviews'])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where('name', 'like', "%$q%")
                   ->orWhere('sku', 'like', "%$q%");
            })
            ->when($categoryId, function ($qr) use ($categoryId) {
                $qr->where('category_id', $categoryId);
            })
            ->where('status', 'active')
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $products->items(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'brand', 'inventory', 'reviews'])->findOrFail($id);
        return response()->json(['ok' => true, 'data' => $product]);
    }
   
    public function categories(Request $req)
    {
        $perPage = max(1, min((int) $req->integer('per_page', 200), 500));
        $q       = (string) $req->get('q', '');

        $query = Category::query()
            ->select('id','name')
            ->when($q, fn($qr)=> $qr->where('name','like',"%$q%"))
            ->when($req->filled('active'), fn($qr)=> $qr->where('is_active', (int)$req->active))
            ->orderBy('name');

        $page = $query->paginate($perPage);

        $meta = [
            'current_page' => $page->currentPage(),
            'last_page'    => $page->lastPage(),
            'per_page'     => $page->perPage(),
            'total'        => $page->total(),
        ];

        return $this->ok($page->items(), $meta);
    }
    public function showDetail($id)
    {
        $product = Product::with(['category', 'brand', 'inventory', 'reviews'])->findOrFail($id);
        return view('user.products.show', compact('product'));
    }
}
