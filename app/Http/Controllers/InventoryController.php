<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // GET /admin/inventories/list?product_id=&q=&low_only=1
    public function list(Request $request)
    {
        $perPage   = (int)($request->get('per_page', 20));
        $productId = $request->get('product_id');
        $q         = trim((string)$request->get('q', ''));
        $lowOnly   = (bool)$request->get('low_only', false);

        $inv = Inventory::query()
            ->with(['product:id,name,sku'])
            ->when($productId, fn($qr) => $qr->where('product_id', $productId))
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('product', function ($p) use ($q) {
                    $p->where('name','like',"%$q%")->orWhere('sku','like',"%$q%");
                });
            })
            ->when($lowOnly, fn($qr) => $qr->whereColumn('stock', '<=', 'low_stock_threshold'))
            ->orderByDesc('id')
            ->paginate($perPage);

        return response()->json([
            'ok' => true,
            'data' => $inv->items(),
            'meta' => [
                'current_page' => $inv->currentPage(),
                'per_page'     => $inv->perPage(),
                'total'        => $inv->total(),
                'last_page'    => $inv->lastPage(),
            ],
        ]);
    }

    // POST /admin/inventories  (tạo record tồn kho cho SP chưa có)
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'           => 'required|exists:products,id',
            'stock'                => 'nullable|integer|min:0',
            'low_stock_threshold'  => 'nullable|integer|min:0',
        ]);

        // đảm bảo mỗi product chỉ có 1 inventory
        if (Inventory::where('product_id', $data['product_id'])->exists()) {
            return response()->json(['ok'=>false,'message'=>'Sản phẩm đã có tồn kho'], 422);
        }

        $inv = Inventory::create([
            'product_id' => $data['product_id'],
            'stock' => $data['stock'] ?? 0,
            'low_stock_threshold' => $data['low_stock_threshold'] ?? 5,
        ]);

        return response()->json(['ok'=>true,'message'=>'Đã tạo tồn kho','data'=>$inv], 201);
    }

    // PUT /admin/inventories/{inventory}
    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'stock'               => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        $inventory->update($data);
        return response()->json(['ok'=>true,'message'=>'Đã cập nhật tồn kho','data'=>$inventory]);
    }

    // DELETE /admin/inventories/{inventory}
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(['ok'=>true,'message'=>'Đã xoá tồn kho']);
    }

    // PUT /admin/inventories/{inventory}/set-stock
    public function setStock(Request $request, Inventory $inventory)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0']);
        $inventory->update(['stock' => $data['stock']]);
        return response()->json(['ok'=>true,'message'=>'Đã đặt lại số lượng','data'=>$inventory]);
    }

    // PUT /admin/inventories/{inventory}/adjust-stock
    public function adjustStock(Request $request, Inventory $inventory)
    {
        $data = $request->validate(['delta' => 'required|integer']); // có thể âm hoặc dương
        $new = max(0, $inventory->stock + $data['delta']);
        $inventory->update(['stock' => $new]);
        return response()->json(['ok'=>true,'message'=>'Đã điều chỉnh số lượng','data'=>$inventory]);
    }
}
