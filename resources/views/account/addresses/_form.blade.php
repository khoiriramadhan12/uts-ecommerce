<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label" for="label">Label</label>
        <input class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $address->label ?? '') }}" placeholder="Rumah / Kantor" required>
        @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="recipient_name">Nama penerima</label>
        <input class="form-control @error('recipient_name') is-invalid @enderror" id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $address->recipientName ?? '') }}" required>
        @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="phone">Telepon</label>
        <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $address->phone ?? '') }}" required>
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label" for="line1">Alamat</label>
        <input class="form-control @error('line1') is-invalid @enderror" id="line1" name="line1" value="{{ old('line1', $address->line1 ?? '') }}" required>
        @error('line1') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="line2">Detail (opsional)</label>
        <input class="form-control" id="line2" name="line2" value="{{ old('line2', $address->line2 ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="city">Kota</label>
        <input class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $address->city ?? '') }}" required>
        @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="province">Provinsi</label>
        <input class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $address->province ?? '') }}" required>
        @error('province') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label" for="postal_code">Kode pos</label>
        <input class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $address->postalCode ?? '') }}" required>
        @error('postal_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" id="is_default" name="is_default" type="checkbox" value="1" @checked(old('is_default', false))>
            <label class="form-check-label" for="is_default">Jadikan alamat utama</label>
        </div>
    </div>
</div>
