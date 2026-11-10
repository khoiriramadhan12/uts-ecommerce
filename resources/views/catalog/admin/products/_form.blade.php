<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Nama produk</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="sku">SKU</label>
        <input class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label" for="category_id">Kategori</label>
        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="price">Harga</label>
        <input class="form-control @error('price') is-invalid @enderror" id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required>
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="stock">Stok</label>
        <input class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required>
        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label d-block">Status</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->is_active ?? true))>
            <label class="form-check-label" for="is_active">Aktif dijual</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Deskripsi</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Tag</label>
        <div class="d-flex flex-wrap gap-3">
            @foreach ($tags as $tag)
                <div class="form-check">
                    <input class="form-check-input" id="tag-{{ $tag->id }}" name="tags[]" type="checkbox" value="{{ $tag->id }}" @checked(in_array($tag->id, old('tags', isset($product) ? $product->tags->pluck('id')->all() : [])))>
                    <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
