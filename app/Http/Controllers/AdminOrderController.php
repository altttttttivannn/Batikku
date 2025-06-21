<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function edit($id)
    {
        $order = Order::with(['user', 'items.barang'])->findOrFail($id);
        return view('admin.order_edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,completed',
        ]);
        
        // Hanya update order_status
        $order->update(['order_status' => $request->order_status]);
        
        return redirect('/admin?tab=transaksi')->with('success', 'Order status updated!');
    }
}
