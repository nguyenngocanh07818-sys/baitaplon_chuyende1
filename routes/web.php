<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;                 // Admin Orders API
use App\Http\Controllers\ProductController;               // Admin Products API
use App\Http\Controllers\CategoryController;              // Admin Categories API
use App\Http\Controllers\BrandController;                 // Admin Brands API
use App\Http\Controllers\InventoryController;             // Admin Inventories API
use App\Http\Controllers\ProductImageController;          // Admin ProductImages API
use App\Http\Controllers\VnPayController;              // VnPay payment
use App\Http\Controllers\User\OrderUserController;       // User Orders (history)
use App\Http\Controllers\User\CartController;             // User cart
use App\Http\Controllers\User\OrderPlaceController;       // User place order
use App\Http\Controllers\PublicProductController;   
use App\Http\Controllers\Admin\AdminOrderController;       // Public product listing
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController; 
use App\Http\Controllers\ReviewController; // danh gia
use App\Http\Controllers\User\QuanLyThongTinCaNhanController; // quan ly thong tin ca nhan

/* -------------------- AUTH + ROOT -------------------- */
Route::redirect('/', '/login');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
Route::post('/register/code', [AuthController::class, 'sendRegisterCode'])->name('register.send_code');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/* -------------------- USER PAGES -------------------- */


Route::prefix('user')->name('user.')->middleware('auth')->group(function () {
    Route::view('/welcome', 'user.home')->name('welcome');
    Route::view('/home', 'user.home')->name('home');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/orders', [OrderUserController::class, 'index'])->name('orders');
    Route::get('/orders/{order}', [OrderUserController::class, 'show'])->name('orders.show');
    Route::get('/account', [QuanLyThongTinCaNhanController::class, 'show'])->name('account');
    Route::put('/account', [QuanLyThongTinCaNhanController::class, 'update'])->name('account.update');
    Route::put('/account/password', [QuanLyThongTinCaNhanController::class, 'updatePassword'])->name('account.password');
});

/* ---- User actions (Cart + đặt hàng) ---- */
Route::middleware('auth')->group(function () {
    Route::post('/order', [OrderController::class, 'store'])->name('order.store');
    Route::post('/user/cart',               [CartController::class, 'add'])->name('cart.add');
    Route::patch('/user/cart/{id}',         [CartController::class, 'update'])->name('cart.update');
    Route::delete('/user/cart/{id}',        [CartController::class, 'remove'])->name('cart.remove');

    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

/* ---- Public product APIs dùng cho trang chủ ---- */
Route::get('/api/products',        [PublicProductController::class, 'index'])->name('public.products.list');
Route::get('/api/products/{id}',   [PublicProductController::class, 'show'])->name('public.products.show');
Route::get('/api/categories', [PublicProductController::class, 'categories'])
    ->name('public.categories.list');
Route::get('/products/{id}', [PublicProductController::class, 'showDetail'])->name('products.show');
// Tách riêng để khớp VNP_RETURN_URL mới
Route::post('/vnpay/create', [VnPayController::class, 'createPayment'])->name('vnpay.create');

Route::get('/checkout/vnpay-return', [VnPayController::class, 'vnpayReturn'])->name('vnpay.return');

Route::get('/vnpay/ipn', [VnPayController::class, 'ipn'])->name('vnpay.ipn');
/* -------------------- ADMIN -------------------- */
Route::prefix('admin')->name('admin.')->middleware(['auth','admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // ===== Views (Blade) =====
    Route::view('/products',     'admin.products.index')->name('products.index');
    Route::view('/categories',   'admin.categories.index')->name('categories.index');
    Route::view('/brands',       'admin.brands.index')->name('brands.index');
    Route::view('/orders',       'admin.orders.index')->name('orders.index');
    Route::view('/inventories',  'admin.inventories.index')->name('inventories.index');
    Route::view('/images',       'admin.images.index')->name('images.index');
    Route::view('/users',        'admin.users.index')->name('users.index');
    Route::get('/reports',       [ReportController::class,'index'])->name('reports.index');


    Route::get('/orders/list',   [AdminOrderController::class, 'list'])->name('orders.list');
    Route::put('/orders/{order}',[AdminOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // ===== Users (Admin CRUD qua AJAX)
    Route::get('/users/list',        [UserController::class,'list'])->name('users.list');
    Route::post('/users',            [UserController::class,'store'])->name('users.store');
    Route::put('/users/{user}',      [UserController::class,'update'])->name('users.update');
    Route::delete('/users/{user}',   [UserController::class,'destroy'])->name('users.destroy');
    // ===== APIs cho AJAX trong admin =====
    // Products
    Route::get('/products/list',         [ProductController::class, 'list'])->name('products.list');
    Route::post('/products',             [ProductController::class, 'store'])->name('products.store');
    Route::put('/products/{product}',    [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories/list',           [CategoryController::class, 'list'])->name('categories.list');
    Route::post('/categories',               [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}',     [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',  [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Brands
    Route::get('/brands/list',           [BrandController::class, 'list'])->name('brands.list');
    Route::post('/brands',               [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/{brand}',        [BrandController::class, 'update'])->name('brands.update');
    Route::delete('/brands/{brand}',     [BrandController::class, 'destroy'])->name('brands.destroy');

    // Orders (Admin quản trị)
    Route::get('/orders/list',           [OrderController::class, 'list'])->name('orders.list');
    Route::post('/orders',               [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/{order}',        [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}',     [OrderController::class, 'destroy'])->name('orders.destroy');

    // Inventories
    Route::get('/inventories/list',          [InventoryController::class,'list'])->name('inventories.list');
    Route::post('/inventories',              [InventoryController::class,'store'])->name('inventories.store');
    Route::put('/inventories/{inventory}',   [InventoryController::class,'update'])->name('inventories.update');
    Route::delete('/inventories/{inventory}',[InventoryController::class,'destroy'])->name('inventories.destroy');
    Route::put('/inventories/{inventory}/set-stock',    [InventoryController::class,'setStock'])->name('inventories.setStock');
    Route::put('/inventories/{inventory}/adjust-stock', [InventoryController::class,'adjustStock'])->name('inventories.adjustStock');

    // Product Images
    Route::get('/images/list',              [ProductImageController::class,'list'])->name('productImages.list');
    Route::post('/images',                  [ProductImageController::class,'store'])->name('productImages.store');
    Route::put('/images/{image}',           [ProductImageController::class,'update'])->name('productImages.update');
    Route::delete('/images/{image}',        [ProductImageController::class,'destroy'])->name('productImages.destroy');
});
/* ---- API proxy cho địa chỉ (thêm vào web.php, trước VNPay Routes) ---- */
Route::get('/api/provinces', [CartController::class, 'getProvinces'])->name('api.provinces');
Route::get('/api/districts/{province_code}', [CartController::class, 'getDistricts'])->name('api.districts');
Route::get('/api/wards/{district_code}', [CartController::class, 'getWards'])->name('api.wards');