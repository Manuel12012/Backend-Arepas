<?php

namespace App\Http\Controllers;  // ← esto falta
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeliverySetting;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required',
            'name' => 'required',
            'delivery' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'scheduled_for' => 'nullable|date|after:now',
            'items' => 'required|array'
        ]);

        $order = null;

        DB::transaction(function () use ($request, &$order) {

            $distanceKm = null;
            $freeDelivery = false;
            $deliveryCost = 0;

            $itemsTotal = 0;

            foreach ($request->items as $item) {
                $itemsTotal += $item['price'] * $item['quantity'];
            }

            if (
                $request->delivery === 'delivery' &&
                $request->latitude !== null &&
                $request->longitude !== null
            ) {

                $settings = DeliverySetting::first();

                if ($settings) {

                    $distanceKm =
                        $this->calculateDistance(
                            (float)$settings->store_latitude,
                            (float)$settings->store_longitude,
                            (float)$request->latitude,
                            (float)$request->longitude
                        );

                    $freeDelivery =
                        $distanceKm <=
                        $settings->free_radius_km;

                    $deliveryCost =
                        $freeDelivery
                        ? 0
                        : $settings->delivery_cost;
                }
            }
            $order = Order::create([
                'email' => $request->email,
                'phone' => $request->phone,
                'name' => $request->name,
                'delivery' => $request->delivery,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'scheduled_for' => $request->scheduled_for,
                'total' => $itemsTotal + $deliveryCost,

                'distance_km' =>
                $distanceKm !== null
                    ? round($distanceKm, 2)
                    : null,

                'free_delivery' =>
                $freeDelivery,

                'delivery_cost' =>
                $deliveryCost,

                'status' => 'Sin asignar'
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
        return Order::with('items')->latest()->paginate(1);
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

    private function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {

        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a =
            sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($dLon / 2) *
            sin($dLon / 2);

        $c =
            2 * atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

        return $earthRadius * $c;
    }
}
