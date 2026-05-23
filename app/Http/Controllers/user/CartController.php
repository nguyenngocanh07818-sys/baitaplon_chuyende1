<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    private function ok($message = 'OK', $data = null)
    {
        return response()->json(['ok' => true, 'message' => $message, 'data' => $data]);
    }
    private function err($message = 'Có lỗi xảy ra', $code = 422)
    {
        return response()->json(['ok' => false, 'message' => $message], $code);
    }

    /** Hiển thị view giỏ hàng */
    public function index()
    {
        
        $cart  = Session::get('cart', []);
        $total = collect($cart)->reduce(fn($t, $i) => $t + ($i['price'] * $i['quantity']), 0);
        return view('user.cart', compact('cart', 'total'));
    }

    /** POST /user/cart  (AJAX) */
    public function add(Request $req)
    {
        $data = $req->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::with(['category:id,name', 'inventory:id,product_id,stock'])->findOrFail($data['product_id']);
        $stock   = (int) ($product->inventory->stock ?? 0);
        if ($stock <= 0) return $this->err('Sản phẩm đã hết hàng');

        $cart = Session::get('cart', []);
        $key  = (string) $product->id;

        $currentQty = isset($cart[$key]) ? (int) $cart[$key]['quantity'] : 0;
        $newQty     = $currentQty + (int) $data['quantity'];
        if ($newQty > $stock) {
            $newQty = $stock;
            if ($currentQty >= $stock) return $this->err('Số lượng trong giỏ đã đạt mức tối đa còn lại');
        }

        $price = (float) ($product->sale_price ?? $product->price ?? 0);

        $cart[$key] = [
            'id'       => $product->id,
            'name'     => $product->name,
            'sku'      => $product->sku,
            'price'    => $price,
            'quantity' => $newQty,
            'category' => $product->category->name ?? null,
            'max'      => $stock,
            'thumbnail' => $product->thumbnail ?: 'https://via.placeholder.com/150',
        ];

        Session::put('cart', $cart);
        return $this->ok('Đã thêm vào giỏ', ['quantity' => $newQty]);
    }

    /** PATCH /user/cart/{id}  (AJAX hoặc form) */
    public function update(Request $req, $id)
    {
        $req->validate(['quantity' => 'required|integer|min:1']);
        $cart = Session::get('cart', []);
        $key = (string) $id;

        if (!isset($cart[$key])) return $this->err('Không tìm thấy sản phẩm trong giỏ', 404);

        // kiểm tra tồn kho hiện tại
        $product = Product::with('inventory:id,product_id,stock')->find($id);
        if (!$product) return $this->err('Sản phẩm không tồn tại', 404);
        $stock = (int) ($product->inventory->stock ?? 0);
        if ($stock <= 0) return $this->err('Sản phẩm đã hết hàng');

        $qty = (int) $req->integer('quantity');
        if ($qty > $stock) $qty = $stock;

        $cart[$key]['quantity'] = $qty;
        $cart[$key]['max']      = $stock;

        Session::put('cart', $cart);

        // Nếu gọi bằng form (HTML), redirect kèm flash; nếu AJAX, trả JSON
        if ($req->expectsJson()) {
            return $this->ok('Đã cập nhật số lượng', ['quantity' => $qty]);
        }
        return redirect()->route('user.cart')->with('success', 'Đã cập nhật số lượng');
    }

    /** DELETE /user/cart/{id} */
    public function remove(Request $req, $id)
    {
        $cart = Session::get('cart', []);
        $key = (string) $id;
        unset($cart[$key]);
        Session::put('cart', $cart);

        if ($req->expectsJson()) {
            return $this->ok('Đã xoá khỏi giỏ');
        }
        return redirect()->route('user.cart')->with('success', 'Đã xoá khỏi giỏ');
    }
    public function getProvinces()
    {
        $response = Http::get('https://provinces.open-api.vn/api/v1/');
        if ($response->successful()) {
            return response()->json($response->json());
        }
        return response()->json(['error' => 'Không thể tải dữ liệu tỉnh'], 500);
    }

    /**
     * Proxy API: Lấy quận/huyện theo tỉnh (code tỉnh)
     */
    public function getDistricts($provinceCode)
    {
        $response = Http::get("https://provinces.open-api.vn/api/p/{$provinceCode}?depth=2");
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data['districts'] ?? []);
        }
        return response()->json(['error' => 'Không thể tải dữ liệu quận'], 500);
    }

    /**
     * Proxy API: Lấy xã/phường theo quận (code quận)
     */
    public function getWards($districtCode)
    {
        $response = Http::get("https://provinces.open-api.vn/api/v1/d/{$districtCode}?depth=2");
        if ($response->successful()) {
            $data = $response->json();
            return response()->json($data['wards'] ?? []);
        }
        return response()->json(['error' => 'Không thể tải dữ liệu xã'], 500);
    }
}