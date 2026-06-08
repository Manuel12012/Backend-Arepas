<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
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

            $path = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );

            $validated['image'] = $path;
        }

        return Product::create(
            $validated
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        Product $product
    ) {

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

            $path = $request
                ->file('image')
                ->store(
                    'products',
                    'public'
                );

            $validated['image'] = $path;
        }

        $product->update(
            $validated
        );

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado'
        ]);
    }

    public function count()
    {

        return response()->json([
            'total' => Product::count()
        ]);
    }
}
