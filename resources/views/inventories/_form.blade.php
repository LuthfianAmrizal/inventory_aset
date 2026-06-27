@csrf

@if ($items->isEmpty())
    <div class="alert alert-warning">
        Data item aktif belum tersedia. Tambahkan master item terlebih dahulu sebelum mengisi inventory.
    </div>
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="item_id" class="form-label">Item</label>
        <select id="item_id" name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
            <option value="">Pilih item</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}" @selected((int) old('item_id', $inventory->item_id) === $item->id)>
                    {{ $item->code }} - {{ $item->name }}
                </option>
            @endforeach
        </select>
        @error('item_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="qty" class="form-label">Qty</label>
        <input
            id="qty"
            type="number"
            name="qty"
            value="{{ old('qty', $inventory->qty) }}"
            class="form-control @error('qty') is-invalid @enderror"
            min="0"
            max="100000000"
            required
        >
        @error('qty')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="price" class="form-label">Harga</label>
        <input
            id="price"
            type="number"
            name="price"
            value="{{ old('price', $inventory->price) }}"
            class="form-control @error('price') is-invalid @enderror"
            min="0"
            max="999999999999.99"
            step="0.01"
            required
        >
        @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="barcode" class="form-label">Barcode</label>
        <input
            id="barcode"
            type="text"
            name="barcode"
            value="{{ old('barcode', $inventory->barcode) }}"
            class="form-control @error('barcode') is-invalid @enderror"
            placeholder="Kosongkan untuk generate otomatis"
            maxlength="100"
        >
        @error('barcode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="expired_date" class="form-label">Expired Date</label>
        <input
            id="expired_date"
            type="date"
            name="expired_date"
            value="{{ old('expired_date', $inventory->expired_date?->format('Y-m-d')) }}"
            class="form-control @error('expired_date') is-invalid @enderror"
        >
        @error('expired_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status Barang</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="available" @selected(old('status', $inventory->status) === 'available')>Available</option>
            <option value="reserved" @selected(old('status', $inventory->status) === 'reserved')>Reserved</option>
            <option value="damaged" @selected(old('status', $inventory->status) === 'damaged')>Damaged</option>
            <option value="lost" @selected(old('status', $inventory->status) === 'lost')>Lost</option>
            <option value="inactive" @selected(old('status', $inventory->status) === 'inactive')>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Catatan</label>
        <textarea
            id="description"
            name="description"
            class="form-control @error('description') is-invalid @enderror"
            rows="4"
            maxlength="1000"
        >{{ old('description', $inventory->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="photo" class="form-label">Foto Barang</label>
        <input
            id="photo"
            type="file"
            name="photo"
            class="form-control @error('photo') is-invalid @enderror"
            accept="image/*"
        >
        @if ($inventory->photo)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $inventory->photo) }}" alt="Foto Barang" class="img-thumbnail" style="max-height: 150px;">
            </div>
        @endif
        @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary" @disabled($items->isEmpty())>
        <i class="bi bi-save me-1"></i> Simpan
    </button>
</div>
