<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $rules = [
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ];

        if ($request->status == 'shipped') {
            $rules['shipping_provider'] = 'required|string';
            $rules['tracking_number'] = 'required|string';
        }

        $request->validate($rules);

        $data = ['status' => $request->status];

        if ($request->filled('shipping_provider')) {
            $data['shipping_provider'] = $request->shipping_provider;
        }

        if ($request->filled('tracking_number')) {
            $data['tracking_number'] = $request->tracking_number;
        }

        if ($request->status == 'shipped' && !$order->shipped_at) {
            $data['shipped_at'] = now();
        }

        $order->update($data);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
    public function invoice($id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);

        // Generate pseudo invoice number if not exists
        if (!$order->tax_invoice_number) {
            $year = date('Y');
            $order->update([
                'tax_invoice_number' => '010.000-' . $year . '.' . str_pad($order->id, 8, '0', STR_PAD_LEFT) // Dummy pattern
            ]);
        }

        return view('admin.orders.invoice', compact('order'));
    }
}
