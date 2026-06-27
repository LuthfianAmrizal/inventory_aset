@csrf

<div class="row g-3">
    <div class="col-md-4">
        <label for="code" class="form-label">Kode</label>
        <input
            id="code"
            type="text"
            name="code"
            value="{{ old('code', $itemType->code) }}"
            class="form-control @error('code') is-invalid @enderror"
            maxlength="30"
            required
            autofocus
        >
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-8">
        <label for="name" class="form-label">Nama Jenis Barang</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $itemType->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            maxlength="150"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea
            id="description"
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
            maxlength="1000"
        >{{ old('description', $itemType->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input
                id="is_active"
                type="checkbox"
                name="is_active"
                value="1"
                class="form-check-input"
                @checked(old('is_active', $itemType->is_active))
            >
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('item-types.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Simpan
    </button>
</div>
