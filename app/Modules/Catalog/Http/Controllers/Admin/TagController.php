<?php

namespace App\Modules\Catalog\Http\Controllers\Admin;

use App\Modules\Catalog\Http\Requests\StoreTagRequest;
use App\Modules\Catalog\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TagController
{
    public function index(): View
    {
        return view('catalog.admin.tags.index', [
            'tags' => Tag::withCount('products')->orderBy('name')->paginate(15),
        ]);
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create([
            'name' => $request->validated('name'),
            'slug' => Str::slug($request->validated('name')).'-'.Str::lower(Str::random(4)),
        ]);

        return redirect()->route('admin.catalog.tags.index')
            ->with('success', 'Tag berhasil ditambahkan.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()->route('admin.catalog.tags.index')
            ->with('success', 'Tag berhasil dihapus.');
    }
}
