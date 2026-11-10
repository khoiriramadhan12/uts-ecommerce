<?php

namespace App\Modules\Catalog\Http\Controllers;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicProductController
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->active()
            ->with(['category', 'tags'])
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('category'), fn ($query) => $query->whereHas(
                'category',
                fn ($category) => $category->where('slug', $request->string('category'))
            ))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('catalog.index', [
            'products' => $products,
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->active()->with(['category', 'tags'])->firstOrFail();

        return view('catalog.show', compact('product'));
    }
}
