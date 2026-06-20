<?php

namespace App\Http\Controllers;

use App\Models\DeliverySetting;
use Illuminate\Http\Request;

class DeliverySettingController extends Controller
{
    public function show()
    {
        return response()->json(
            DeliverySetting::first()
        );
    }

    public function update(Request $request)
    {
        $request->validate([
            'store_latitude' => 'required|numeric',
            'store_longitude' => 'required|numeric',
            'free_radius_km' => 'required|integer|min:1',
            'delivery_cost' => 'required|numeric|min:0',
        ]);

        $settings = DeliverySetting::first();

        $settings->update([
            'store_latitude' => $request->store_latitude,
            'store_longitude' => $request->store_longitude,
            'free_radius_km' => $request->free_radius_km,
            'delivery_cost' => $request->delivery_cost,
        ]);

        return response()->json([
            'message' => 'Configuración actualizada',
            'data' => $settings
        ]);
    }
}