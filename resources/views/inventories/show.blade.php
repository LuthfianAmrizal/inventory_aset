<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Detail Inventory</h1>
                <div class="text-muted">{{ $inventory->item?->code }} - {{ $inventory->item?->name }}</div>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Update Stok
                    </a>
                @endif
                <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <div class="row">
                <div class="{{ $inventory->photo ? 'col-md-8' : 'col-12' }}">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Item</dt>
                        <dd class="col-sm-9">{{ $inventory->item?->name ?: '-' }}</dd>

                        <dt class="col-sm-3">Kode Item</dt>
                        <dd class="col-sm-9">{{ $inventory->item?->code ?: '-' }}</dd>

                        <dt class="col-sm-3">Qty</dt>
                        <dd class="col-sm-9">{{ number_format($inventory->qty) }}</dd>

                        <dt class="col-sm-3">Harga</dt>
                        <dd class="col-sm-9">Rp {{ number_format((float) $inventory->price, 2, ',', '.') }}</dd>

                        <dt class="col-sm-3">Barcode</dt>
                        <dd class="col-sm-9">
                            @if ($inventory->barcode)
                                <div class="fw-semibold mb-2">{{ $inventory->barcode }}</div>
                                <div class="d-flex flex-wrap gap-3 align-items-center mt-2">
                                    <!-- Visual Barcode -->
                                    <div class="bg-white p-2 border rounded shadow-sm text-center">
                                        <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ urlencode($inventory->barcode) }}&scale=2&rotate=N&includetext" alt="Barcode {{ $inventory->barcode }}" style="max-width: 100%; height: 50px;">
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">Code128 Barcode</div>
                                    </div>
                                    <!-- QR Code -->
                                    <div class="bg-white p-2 border rounded shadow-sm text-center">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode($inventory->barcode) }}" alt="QR {{ $inventory->barcode }}" style="width: 70px; height: 70px;">
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">QR Code</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </dd>

                        <dt class="col-sm-3">Expired Date</dt>
                        <dd class="col-sm-9">{{ $inventory->expired_date?->format('d M Y') ?: '-' }}</dd>

                        <dt class="col-sm-3">Status Barang</dt>
                        <dd class="col-sm-9">
                            <span class="badge text-bg-info">{{ Str::headline($inventory->status) }}</span>
                        </dd>

                        <dt class="col-sm-3">Catatan</dt>
                        <dd class="col-sm-9">{{ $inventory->description ?: '-' }}</dd>

                        <dt class="col-sm-3">Dibuat</dt>
                        <dd class="col-sm-9">{{ $inventory->created_at?->format('d M Y H:i') }}</dd>

                        <dt class="col-sm-3">Diperbarui</dt>
                        <dd class="col-sm-9">{{ $inventory->updated_at?->format('d M Y H:i') }}</dd>
                    </dl>
                </div>
                @if ($inventory->photo)
                    <div class="col-md-4 text-center border-start d-flex align-items-center justify-content-center">
                        <div class="p-2">
                            <div class="text-muted mb-2 small font-weight-bold uppercase">Foto Barang</div>
                            <img src="{{ asset('storage/' . $inventory->photo) }}" alt="Foto {{ $inventory->item?->name }}" class="img-fluid rounded shadow-sm border" style="max-height: 250px; object-fit: cover;">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
