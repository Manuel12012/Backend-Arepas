<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        return Offer::with("product")
            ->select([
                "id",
                "inicio",
                "fin",
                "descuento"
            ])
            ->orderBy("inicio")
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "inicio" => "required",
            "fin" => "required",
            "descuento" => "required"

        ]);

        $offer = Offer::create($validated);

        return response()->json($offer, 201);
    }

    public function update(Request $request, Offer $offer)
    {

        $validated = $request->validate([
            "inicio" => "required",
            "fin" => "required",
            "descuento" => "required"

        ]);

        $offer->update($validated);

        return $offer->fresh();
    }

    public function destroy(Offer $offer)
    {

        $offer->delete();

        return response()->json([
            "message" => "Oferta eliminada"
        ]);
    }
}
