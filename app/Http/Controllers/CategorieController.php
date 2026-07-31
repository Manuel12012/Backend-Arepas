<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategorieController extends Controller
{
    public function index()
    {
        return Categories::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "nombre" => "required",
            "image" => "nullable|image|max:5120"
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $categorie = Categories::create($validated);

        Cache::forget('categories');
        return $categorie;
    }

    public function update(Request $request, Categories $category)
    {
        $validated = $request->validate([
            "nombre" => "required",
            "image" => "nullable|image|max:5120"
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $category->update($validated);

        return $category;
    }

    public function destroy(Categories $category)
    {
        $id = $category->id;

        $deleted = $category->delete();

        return response()->json([
            'id' => $id,
            'deleted' => $deleted,
            'find' => Categories::find($id),
            'count' => Categories::count(),
        ]);
    }

    public function count()
    {
        return response()->json(['total' => Categories::count()]);
    }
}
