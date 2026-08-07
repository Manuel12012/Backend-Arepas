<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

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
            $validated['image'] = $request->file('image')->store('categories', 's3');
        }

        $categorie = Categories::create($validated);

        Cache::forget('categories');
        return response()->json($categorie, 201);
    }

    public function update(Request $request, Categories $category)
    {
        $validated = $request->validate([
            "nombre" => "required",
            "image" => "nullable|image|max:5120"
        ]);
        if ($request->hasFile('image')) {

            if ($category->getRawOriginal('image')) {
                Storage::disk('s3')->delete($category->getRawOriginal('image'));
            }

            $validated['image'] = $request->file('image')->store('categories', 's3');
        }

        $category->update($validated);

        Cache::forget('categories');

        return $category->fresh();
    }

    public function destroy(Categories $category)
    {
        if ($category->getRawOriginal('image')) {
            Storage::disk('s3')->delete($category->getRawOriginal('image'));
        }

        $category->delete();

        Cache::forget('categories');

        return response()->json([
            'message' => 'Categoria eliminada'
        ]);
    }

    public function count()
    {
        return response()->json(['total' => Categories::count()]);
    }
}
