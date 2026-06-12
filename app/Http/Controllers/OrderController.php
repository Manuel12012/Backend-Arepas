<?php

namespace App\Http\Controllers;  // ← esto falta
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'name' => 'required',
            'delivery' => 'required',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'items' => 'required|array'
        ]);

        $order = null;

        DB::transaction(function () use ($request, &$order) {

            $total = 0;

            foreach ($request->items as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $order = Order::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'delivery' => $request->delivery,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total' => $total,
                'status' => "Sin asignar"
            ]);

            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'name_snapshot' => $item['name'],
                    'price_snapshot' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }
        });

        return response()->json([
            'message' => 'Order created',
            'order' => $order
        ]);
    }

    public function index()
    {
        return Order::with('items')->latest()->get();
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Sin asignar,Entregado,Cancelado'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return response()->json($order);
    }
}
