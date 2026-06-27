<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <div class="text-muted">Ringkasan awal Sistem Informasi Inventory Aset</div>
            </div>
            <span class="badge text-bg-primary px-3 py-2 fs-6 rounded-pill shadow-sm">{{ Str::headline(Auth::user()->role) }}</span>
        </div>
    </x-slot>

    <!-- Stat Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Stok -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-50 end-0 translate-middle-y me-3 opacity-25">
                        <i class="bi bi-box-seam" style="font-size: 4rem;"></i>
                    </div>
                    <div class="text-white-50 small text-uppercase fw-semibold mb-1">Total Stok Aset</div>
                    <div class="h2 mb-1 fw-bold">{{ number_format($stats['total_inventory_qty']) }}</div>
                    <div class="small text-white-50">Dari {{ number_format($stats['total_items']) }} jenis barang master</div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Nilai Aset -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden" style="background: linear-gradient(135deg, #198754, #157347); color: white;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-50 end-0 translate-middle-y me-3 opacity-25">
                        <i class="bi bi-currency-dollar" style="font-size: 4rem;"></i>
                    </div>
                    <div class="text-white-50 small text-uppercase fw-semibold mb-1">Total Estimasi Nilai</div>
                    <div class="h2 mb-1 fw-bold">Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}</div>
                    <div class="small text-white-50">Nilai investasi barang saat ini</div>
                </div>
            </div>
        </div>

        <!-- Card 3: Transaksi & Realisasi -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden" style="background: linear-gradient(135deg, #0dcaf0, #31d2f2); color: #084298;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-50 end-0 translate-middle-y me-3 opacity-25">
                        <i class="bi bi-journal-text" style="font-size: 4rem;"></i>
                    </div>
                    <div class="text-blue-50 small text-uppercase fw-semibold mb-1">Total Realisasi Transaksi</div>
                    <div class="h2 mb-1 fw-bold">Rp {{ number_format($stats['total_realization'], 0, ',', '.') }}</div>
                    <div class="small text-blue-50">Dari {{ number_format($stats['total_transactions']) }} total transaksi tercatat</div>
                </div>
            </div>
        </div>

        <!-- Card 4: Ruang & Gedung -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffc107, #ffca2c); color: #664d03;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute top-50 end-0 translate-middle-y me-3 opacity-25">
                        <i class="bi bi-building" style="font-size: 4rem;"></i>
                    </div>
                    <div class="text-dark-50 small text-uppercase fw-semibold mb-1">Lokasi Aset</div>
                    <div class="h2 mb-1 fw-bold">{{ number_format($stats['total_rooms']) }} Ruangan</div>
                    <div class="small text-dark-50">Tersebar di {{ number_format($stats['total_buildings']) }} gedung kampus</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tables -->
    <div class="row g-4">
        <!-- Recent Transactions -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="bi bi-clock-history text-primary me-2"></i>Transaksi Terbaru
                    </h5>
                    <a href="{{ route('inventory-transactions.index') }}" class="btn btn-sm btn-light rounded-pill px-3 text-primary fw-semibold">Lihat Semua</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th>No. Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Jenis</th>
                                    <th class="text-end">Realisasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $trx)
                                    <tr>
                                        <td>
                                            <a href="{{ route('inventory-transactions.show', $trx) }}" class="fw-semibold text-decoration-none">
                                                {{ $trx->transaction_number }}
                                            </a>
                                        </td>
                                        <td class="text-muted small">{{ $trx->transaction_date?->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge text-bg-light border text-dark">{{ $trx->transactionType?->name }}</span>
                                        </td>
                                        <td class="text-end fw-semibold text-success">
                                            Rp {{ number_format($trx->realization, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada transaksi tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent/Low Stock Inventories -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="bi bi-box-seam text-success me-2"></i>Stok Terbaru
                    </h5>
                    <a href="{{ route('inventories.index') }}" class="btn btn-sm btn-light rounded-pill px-3 text-success fw-semibold">Lihat Semua</a>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th style="width: 60px;">Foto</th>
                                    <th>Barang</th>
                                    <th class="text-end">Stok</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentInventories as $inv)
                                    <tr>
                                        <td>
                                            @if ($inv->photo)
                                                <img src="{{ asset('storage/' . $inv->photo) }}" alt="Foto" class="rounded border" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-image" style="font-size: 1rem;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-truncate" style="max-width: 150px;">{{ $inv->item?->name }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $inv->item?->code }}</div>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($inv->qty) }}</td>
                                        <td>
                                            <span class="badge text-bg-info" style="font-size: 0.75rem;">{{ Str::headline($inv->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada data barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
