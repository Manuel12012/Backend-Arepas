<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'offer'])
            ->select([
                'id',
                'category_id',
                'offer_id',
                'nombre',
                'descripcion',
                'precio',
                'combo',
                'unidadCombo',
                'image',
            ])
            ->orderBy('nombre');

        if ($request->boolean('without_offer')) {
            $query->whereNull('offer_id');
        }

        return $query->get();
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required',
            'combo' => 'nullable',
            'unidadCombo' => 'nullable',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 's3');
        }

        $product = Product::create($validated);

        Cache::forget('products');

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'offer',
        ]);

        return response()->json($product);
    }
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required',
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required',
            'combo' => 'nullable',
            'unidadCombo' => 'nullable',
            'image' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('image')) {

            if ($product->getRawOriginal('image')) {
                Storage::disk('s3')->delete($product->getRawOriginal('image'));
            }

            $validated['image'] = $request->file('image')->store('products', 's3');
        }

        $product->update($validated);

        Cache::forget('products');

        return $product->fresh();
    }

    public function destroy(Product $product)
    {
        if ($product->getRawOriginal('image')) {
            Storage::disk('s3')->delete($product->getRawOriginal('image'));
        }

        $product->delete();

        Cache::forget('products');

        return response()->json([
            'message' => 'Producto eliminado'
        ]);
    }

    public function count()
    {
        return response()->json(['total' => Product::count()]);
    }

    public function countCategorias()
    {
        return response()->json([
            'total' => Product::distinct('category_id')->count('category_idc')
        ]);
    }

    public function assignOffer(Request $request, Product $product)
    {
        $validated = $request->validate([
            'offer_id' => 'required|exists:offer,id',
        ]);

        $product->offer_id = $validated['offer_id'];
        $product->save();

        Cache::forget('products');

        return response()->json(
            $product->fresh()->load('offer')
        );
    }
}
