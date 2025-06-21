<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Http\Controllers\AdminBarangController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DetailProdukController;
use App\Http\Controllers\AdminOrderController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $produkSlider = \App\Models\Barang::with('stokUkuran')->orderBy('created_at', 'desc')->take(10)->get();
    return view('home', compact('produkSlider'));
});

// Login page
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect('/');
    }
    session()->put('url.intended', url()->previous());
    return view('login');
})->name('login');

// Login process
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        if (Auth::user()->role === 'admin') {
            return redirect('/admin');
        }
        
        // Redirect ke URL sebelumnya atau ke homepage jika tidak ada
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah!',
    ])->onlyInput('email');
});

// Register page
Route::get('/register', function () {
    return view('register');
});

// Register process
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => ['required', 'email', 'unique:users,email', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        'password' => 'required|min:6',
    ], [
        'email.regex' => 'Format email tidak valid. Gunakan format: nama@domain.com'
    ]);
    
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',
    ]);
    
    session(['user_id' => $user->id, 'user_role' => $user->role]);
    return redirect('/');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// Route admin management (hanya untuk admin)
Route::get('/admin', function () {
    if (!Auth::check() || Auth::user()->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Get products for list and search with stock information
    $query = \App\Models\Barang::with('stokUkuran')->whereNull('deleted_at');
    if (request('q')) {
        $query->where('nama', 'like', '%' . request('q') . '%');
    }
    if (request('kategori')) {
        $query->where('kategori', request('kategori'));
    }
    $barangs = $query->get();
    
    // Get orders (with user and items.barang)
    $orders = \App\Models\Order::with(['user', 'items.barang'])->orderByDesc('created_at')->get();

    // Graph data (tetap pakai transaksi lama untuk grafik)
    $filter = request('filter', 'bulan');
    $produkIds = request('produk_id', []);
    if (!is_array($produkIds)) {
        $produkIds = [];
    }
    $labels = collect();
    $datasets = collect();
    $colors = [
        'rgba(59,130,246,1)',  // blue
        'rgba(220,38,38,1)',   // red
        'rgba(5,150,105,1)',   // green
        'rgba(217,119,6,1)',   // orange
        'rgba(139,92,246,1)',  // purple
        'rgba(236,72,153,1)',  // pink
    ];

    // If no specific products selected, show total of all products
    if (empty($produkIds)) {
        $trxQuery = \App\Models\Transaksi::where('status', 'success');
        
        if ($filter === 'hari') {
            $data = $trxQuery->select(
                DB::raw('DATE(created_at) as label'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('label')
            ->orderBy('label')
            ->get();
            
            $labels = $data->pluck('label')->map(function($date) {
                return date('d M', strtotime($date));
            });
            
            $datasets[] = [
                'label' => 'Total Semua Produk',
                'data' => $data->pluck('total'),
                'borderColor' => $colors[0],
                'backgroundColor' => str_replace(',1)', ',0.1)', $colors[0]),
                'tension' => 0.4,
                'fill' => true
            ];
        } elseif ($filter === 'minggu') {
            $data = $trxQuery->select(
                DB::raw('YEARWEEK(created_at, 1) as label'),
                DB::raw('SUM(total_harga) as total'),
                DB::raw('MIN(created_at) as week_start')
            )
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('label')
            ->orderBy('label')
            ->get();
            
            $labels = $data->map(function($item) {
                return 'Minggu ' . date('W', strtotime($item->week_start));
            });
            
            $datasets[] = [
                'label' => 'Total Semua Produk',
                'data' => $data->pluck('total'),
                'borderColor' => $colors[0],
                'backgroundColor' => str_replace(',1)', ',0.1)', $colors[0]),
                'tension' => 0.4,
                'fill' => true
            ];
        } else {
            $data = $trxQuery->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as label'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('label')
            ->orderBy('label')
            ->get();
            
            $labels = $data->map(function($item) {
                return date('F Y', strtotime($item->label . '-01'));
            });
            
            $datasets[] = [
                'label' => 'Total Semua Produk',
                'data' => $data->pluck('total'),
                'borderColor' => $colors[0],
                'backgroundColor' => str_replace(',1)', ',0.1)', $colors[0]),
                'tension' => 0.4,
                'fill' => true
            ];
        }
    } else {
        // Show data for selected products
        foreach ($produkIds as $index => $produkId) {
            $trxQuery = \App\Models\Transaksi::where('status', 'success')
                ->where('barang_id', $produkId);
            
            $barang = $barangs->firstWhere('id', $produkId);
            $colorIndex = $index % count($colors);
                
            if ($filter === 'hari') {
                $data = $trxQuery->select(
                    DB::raw('DATE(created_at) as label'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
                
                if ($index === 0) {
                    $labels = $data->pluck('label')->map(function($date) {
                        return date('d M', strtotime($date));
                    });
                }
                
                $datasets[] = [
                    'label' => $barang->nama,
                    'data' => $data->pluck('total'),
                    'borderColor' => $colors[$colorIndex],
                    'backgroundColor' => str_replace(',1)', ',0.1)', $colors[$colorIndex]),
                    'tension' => 0.4,
                    'fill' => true
                ];
            } elseif ($filter === 'minggu') {
                $data = $trxQuery->select(
                    DB::raw('YEARWEEK(created_at, 1) as label'),
                    DB::raw('SUM(total_harga) as total'),
                    DB::raw('MIN(created_at) as week_start')
                )
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
                
                if ($index === 0) {
                    $labels = $data->map(function($item) {
                        return 'Minggu ' . date('W', strtotime($item->week_start));
                    });
                }
                
                $datasets[] = [
                    'label' => $barang->nama,
                    'data' => $data->pluck('total'),
                    'borderColor' => $colors[$colorIndex],
                    'backgroundColor' => str_replace(',1)', ',0.1)', $colors[$colorIndex]),
                    'tension' => 0.4,
                    'fill' => true
                ];
            } else {
                $data = $trxQuery->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as label'),
                    DB::raw('SUM(total_harga) as total')
                )
                ->whereYear('created_at', date('Y'))
                ->groupBy('label')
                ->orderBy('label')
                ->get();
                
                if ($index === 0) {
                    $labels = $data->map(function($item) {
                        return date('F Y', strtotime($item->label . '-01'));
                    });
                }
                
                $datasets[] = [
                    'label' => $barang->nama,
                    'data' => $data->pluck('total'),
                    'borderColor' => $colors[$colorIndex],
                    'backgroundColor' => str_replace(',1)', ',0.1)', $colors[$colorIndex]),
                    'tension' => 0.4,
                    'fill' => true
                ];
            }
        }
    }
    
    // Get kategori unik untuk filter
    $kategoriList = \App\Models\Barang::distinct()->pluck('kategori')->filter()->values();

    return view('admin', compact('barangs', 'orders', 'labels', 'datasets', 'filter', 'kategoriList'));
});

// CRUD Barang
Route::get('/admin/barang/create', [AdminBarangController::class, 'create']);
Route::post('/admin/barang', [AdminBarangController::class, 'store']);
Route::get('/admin/barang/{id}/edit', [AdminBarangController::class, 'edit']);
Route::put('/admin/barang/{id}', [AdminBarangController::class, 'update']);
Route::delete('/admin/barang/{id}', [AdminBarangController::class, 'destroy']);

// CRUD Transaksi (edit & delete saja)
Route::get('/admin/transaksi/{id}/edit', [AdminTransaksiController::class, 'edit']);
Route::put('/admin/transaksi/{id}', [AdminTransaksiController::class, 'update']);
Route::delete('/admin/transaksi/{id}', [AdminTransaksiController::class, 'destroy']);

// CRUD Order (edit & update)
Route::get('/admin/order/{id}/edit', [AdminOrderController::class, 'edit']);
Route::put('/admin/order/{id}', [AdminOrderController::class, 'update']);

// Katalog
Route::get('/katalog', [KatalogController::class, 'index'])->name('katalog');

// Route untuk checkout (harus login)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/payment/{order_id}', [CheckoutController::class, 'showPayment'])->name('payment');
    Route::post('/payment/{order_id}/confirm', [CheckoutController::class, 'confirmPayment'])->name('payment.confirm');
    Route::get('/order/{order_id}/success', [CheckoutController::class, 'orderSuccess'])->name('order.success');
    
    // History pesanan user
    Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
});

// Detail Produk
Route::get('/produk/{id}', [DetailProdukController::class, 'show'])->name('produk.detail');

// Cart routes (harus login)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
}); 
Route::match(['post', 'patch', 'delete'], 'cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');



Route::post('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');Route::resource('cart', CartController::class)->except(['checkout']);

// About page
Route::get('/about', function () {
    return view('about');
})->name('about');

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});