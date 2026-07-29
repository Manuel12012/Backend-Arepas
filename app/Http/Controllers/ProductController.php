<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index()
    {
        return Cache::remember('products', 3600, function () {
            return Product::select([
                'id', 'categoriaId', 'nombre', 'descripcion',
                'precio', 'combo', 'unidadCombo', 'image',
            ])->orderBy('nombre')->get();
        });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoriaId' => 'required',
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required',
            'combo' => 'nullable',
            'unidadCombo' => 'nullable',
            'image' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($validated);

        Cache::forget('products');   // 👈 invalida el caché

        return $product;
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'categoriaId' => 'required',
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'precio' => 'required',
            'combo' => 'nullable',
            'unidadCombo' => 'nullable',
            'image' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        Cache::forget('products');   // 👈 invalida el caché

        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();

        Cache::forget('products');   // 👈 invalida el caché

        return response()->json(['message' => 'Producto eliminado']);
    }

    public function count()
    {
        return response()->json(['total' => Product::count()]);
    }

    public function countCategorias()
    {
        return response()->json([
            'total' => Product::distinct('categoriaId')->count('categoriaId')
        ]);
    }
}