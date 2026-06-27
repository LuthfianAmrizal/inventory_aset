@csrf

@if ($transactionTypes->isEmpty())
    <div class="alert alert-warning">
        Data jenis transaksi aktif belum tersedia. Tambahkan Transaction Types terlebih dahulu sebelum membuat transaksi inventory.
    </div>
@endif

<div class="row g-3">
    @if ($inventoryTransaction->exists)
        <div class="col-md-4">
            <label for="transaction_number" class="form-label">Nomor Transaksi</label>
            <input id="transaction_number" type="text" value="{{ $inventoryTransaction->transaction_number }}" class="form-control" disabled>
        </div>
    @endif

    <div class="col-md-{{ $inventoryTransaction->exists ? '8' : '6' }}">
        <label for="transaction_type_id" class="form-label">Jenis Transaksi</label>
        <select id="transaction_type_id" name="transaction_type_id" class="form-select @error('transaction_type_id') is-invalid @enderror" required>
            <option value="">Pilih jenis transaksi</option>
            @foreach ($transactionTypes as $transactionType)
                <option value="{{ $transactionType->id }}" @selected((int) old('transaction_type_id', $inventoryTransaction->transaction_type_id) === $transactionType->id)>
                    {{ $transactionType->code }} - {{ $transactionType->name }}
                </option>
            @endforeach
        </select>
        @error('transaction_type_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-{{ $inventoryTransaction->exists ? '4' : '6' }}">
        <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
        <input
            id="transaction_date"
            type="date"
            name="transaction_date"
            value="{{ old('transaction_date', $inventoryTransaction->transaction_date?->format('Y-m-d')) }}"
            class="form-control @error('transaction_date') is-invalid @enderror"
            required
        >
        @error('transaction_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="budget" class="form-label">Budget</label>
        <input
            id="budget"
            type="number"
            name="budget"
            value="{{ old('budget', $inventoryTransaction->budget) }}"
            class="form-control @error('budget') is-invalid @enderror"
            min="0"
            max="999999999999.99"
            step="0.01"
            required
        >
        @error('budget')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="realization" class="form-label">Realisasi</label>
        <input
            id="realization"
            type="number"
            name="realization"
            value="{{ old('realization', $inventoryTransaction->realization) }}"
            class="form-control @error('realization') is-invalid @enderror"
            min="0"
            max="999999999999.99"
            step="0.01"
            required
        >
        @error('realization')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="notes" class="form-label">Catatan</label>
        <textarea
            id="notes"
            name="notes"
            class="form-control @error('notes') is-invalid @enderror"
            rows="4"
            maxlength="1000"
        >{{ old('notes', $inventoryTransaction->notes) }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="evidence" class="form-label">Bukti Transaksi (Evidence)</label>
        <input
            id="evidence"
            type="file"
            name="evidence"
            class="form-control @error('evidence') is-invalid @enderror"
            accept=".pdf,image/*"
        >
        @if ($inventoryTransaction->evidence)
            <div class="mt-2">
                <a href="{{ asset('storage/' . $inventoryTransaction->evidence) }}" target="_blank" class="btn btn-sm btn-outline-info">
                    <i class="bi bi-file-earmark-text me-1"></i> Lihat Evidence Saat Ini
                </a>
            </div>
        @endif
        @error('evidence')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ route('inventory-transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
    <button type="submit" class="btn btn-primary" @disabled($transactionTypes->isEmpty())>
        <i class="bi bi-save me-1"></i> Simpan
    </button>
</div>
