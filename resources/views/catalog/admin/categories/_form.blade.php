<div class="mb-3">
    <label class="form-label" for="name">Nama kategori</label>
    <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label class="form-label" for="description">Deskripsi</label>
    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="form-check form-switch mb-3">
    <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $category->is_active ?? true))>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
