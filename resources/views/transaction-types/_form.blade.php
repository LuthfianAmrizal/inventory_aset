@csrf

<div class="row g-3">
    <div class="col-md-4">
        <label for="code" class="form-label">Kode</label>
        <input
            id="code"
            type="text"
            name="code"
            value="{{ old('code', $transactionType->code) }}"
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
        <label for="name" class="form-label">Nama Tipe Transaksi</label>
        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name', $transactionType->name) }}"
            class="form-control @error('name') is-invalid @enderror"
            maxlength="150"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="direction" class="form-label">Arah Transaksi</label>
        <select id="direction" name="direction" class="form-select @error('direction') is-invalid @enderror" required>
            <option value="">Pilih arah transaksi</option>
            <option value="in" @selected(old('direction', $transactionType->direction) === 'in')>In</option>
            <option value="out" @selected(old('direction', $transactionType->direction) === 'out')>Out</option>
            <option value="transfer" @selected(old('direction', $transactionType->direction) === 'transfer')>Transfer</option>
            <option value="adjustment" @selected(old('direction', $transactionType->direction) === 'adjustment')>Adjustment</option>
        </select>
        @error('direction')
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
        >{{ old('description', $transactionType->description) }}</textarea>
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
                @checked(old('is_active', $transactionType->is_active))
            >
            <label for="is_active" class="form-check-label">Aktif</label>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('transaction-types.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i> Simpan
    </button>
</div>
