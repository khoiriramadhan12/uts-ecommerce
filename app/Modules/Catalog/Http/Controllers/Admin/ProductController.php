<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Modules\Catalog\Http\Requests\StoreProductRequest;
use App\Modules\Catalog\Http\Requests\UpdateProductRequest;
use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController
{
    public function index(Request $request): View
    {
        $products = Product::with(['category', 'tags'])
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->string('q').'%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('catalog.admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('catalog.admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $product = Product::create([
            'category_id' => $data['category_id'],
            'sku' => strtoupper($data['sku']),
            'name' => $data['name'],
            'slug' => $this->uniqueSlug($data['name']),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.catalog.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('catalog.admin.products.edit', [
            'product' => $product->load('tags'),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        $product->update([
            'category_id' => $data['category_id'],
            'sku' => strtoupper($data['sku']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'stock' => $data['stock'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $product->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.catalog.products.index')
            ->with('success', 'Produk "'.$product->name.'" berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.catalog.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 1;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
