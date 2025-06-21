<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UkuranStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    const SHIPPING_COST = 15000; // Fixed shipping cost in Rupiah

    public function showCheckout()
    {
        $user = Auth::user();
        $cartItems = Cart::with(['barang', 'barang.stokUkuran'])
            ->where('user_id', $user->id)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            $stokUkuran = $item->barang->stokUkuran()
                ->where('ukuran', $item->ukuran)
                ->first();

            if (!$stokUkuran || $stokUkuran->stok < $item->jumlah) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok tidak mencukupi untuk produk {$item->barang->nama}");
            }
        }

        $total = $cartItems->sum(function($item) {
            return $item->barang->harga * $item->jumlah;
        });

        return view('checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
            'shipping_cost' => self::SHIPPING_COST,
            'grand_total' => $total + self::SHIPPING_COST
        ]);
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string'
        ]);

        $user = Auth::user();
        $cartItems = Cart::with('barang')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja Anda kosong');
        }

        DB::beginTransaction();

        try {
            // Calculate total
            $total = $cartItems->sum(function($item) {
                return $item->barang->harga * $item->jumlah;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'shipping_cost' => self::SHIPPING_COST,
                'payment_status' => 'pending',
                'order_status' => 'pending'
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                // Check stock availability
                $ukuranStok = UkuranStok::where('barang_id', $cartItem->barang_id)
                    ->where('ukuran', $cartItem->ukuran)
                    ->first();

                if (!$ukuranStok || $ukuranStok->stok < $cartItem->jumlah) {
                    throw new \Exception("Stok tidak mencukupi untuk produk " . $cartItem->barang->nama);
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'barang_id' => $cartItem->barang_id,
                    'ukuran' => $cartItem->ukuran,
                    'quantity' => $cartItem->jumlah,
                    'price' => $cartItem->barang->harga
                ]);

                // Tambahkan ke tabel transaksi
                \App\Models\Transaksi::create([
                    'user_id' => $user->id,
                    'barang_id' => $cartItem->barang_id,
                    'jumlah' => $cartItem->jumlah,
                    'total_harga' => $cartItem->barang->harga * $cartItem->jumlah,
                    'status' => 'pending',
                    'ukuran' => $cartItem->ukuran
                ]);

                // Update stock
                $ukuranStok->stok -= $cartItem->jumlah;
                $ukuranStok->save();
            }

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            // Redirect to payment page
            return redirect()->route('payment', ['order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function showPayment($order_id)
    {
        $order = Order::with(['items.barang'])->findOrFail($order_id);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment', [
            'order' => $order
        ]);
    }

    public function confirmPayment($order_id)
    {
        $order = Order::findOrFail($order_id);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->payment_status = 'paid';
        $order->order_status = 'processing';
        $order->save();

        // Sinkronkan status transaksi
        foreach ($order->items as $item) {
            \App\Models\Transaksi::where([
                'user_id' => $order->user_id,
                'barang_id' => $item->barang_id,
                'jumlah' => $item->quantity,
                'ukuran' => $item->ukuran,
                'status' => 'pending',
            ])->update(['status' => 'success']);
        }

        return redirect()->route('order.success', ['order_id' => $order->id]);
    }

    public function orderSuccess($order_id)
    {
        $order = Order::with(['items.barang'])->findOrFail($order_id);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('order-success', [
            'order' => $order
        ]);
    }

    public function orderHistory()
    {
        $orders = \App\Models\Order::with(['items.barang'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('order_history', compact('orders'));
    }
}
