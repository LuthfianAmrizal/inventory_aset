<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Detail Transaksi Inventory</h1>
                <div class="text-muted">{{ $inventoryTransaction->transaction_number }}</div>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('inventory-transactions.edit', $inventoryTransaction) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                @endif
                <a href="{{ route('inventory-transactions.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Nomor Transaksi</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->transaction_number }}</dd>

                <dt class="col-sm-3">Jenis Transaksi</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->transactionType?->name ?: '-' }}</dd>

                <dt class="col-sm-3">Tanggal Transaksi</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->transaction_date?->format('d M Y') }}</dd>

                <dt class="col-sm-3">Budget</dt>
                <dd class="col-sm-9">Rp {{ number_format((float) $inventoryTransaction->budget, 2, ',', '.') }}</dd>

                <dt class="col-sm-3">Realisasi</dt>
                <dd class="col-sm-9">Rp {{ number_format((float) $inventoryTransaction->realization, 2, ',', '.') }}</dd>

                <dt class="col-sm-3">Catatan</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->notes ?: '-' }}</dd>

                <dt class="col-sm-3">Evidence (Bukti)</dt>
                <dd class="col-sm-9">
                    @if ($inventoryTransaction->evidence)
                        @php
                            $extension = pathinfo($inventoryTransaction->evidence, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpeg', 'jpg', 'png', 'gif', 'webp']);
                        @endphp

                        @if ($isImage)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $inventoryTransaction->evidence) }}" alt="Evidence" class="img-fluid rounded border shadow-sm" style="max-height: 200px; object-fit: contain;">
                            </div>
                        @endif

                        <a href="{{ asset('storage/' . $inventoryTransaction->evidence) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Unduh / Lihat Full Evidence ({{ strtoupper($extension) }})
                        </a>
                    @else
                        <span class="text-muted">Tidak ada evidence yang diunggah</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Dibuat</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->created_at?->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Diperbarui</dt>
                <dd class="col-sm-9">{{ $inventoryTransaction->updated_at?->format('d M Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
