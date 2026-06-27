<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Inventory Transactions</h1>
                <div class="text-muted">Kelola transaksi inventory, budget, dan realisasi.</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('inventory-transactions.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
                </a>
            @endif
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card stat-card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('inventory-transactions.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nomor, jenis transaksi, atau catatan">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Filter Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="all" @selected($status === 'all')>Semua</option>
                        <option value="trashed" @selected($status === 'trashed')>Terhapus</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('inventory-transactions.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>Jenis Transaksi</th>
                        <th>Tanggal</th>
                        <th>Budget</th>
                        <th>Realisasi</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventoryTransactions as $inventoryTransaction)
                        <tr>
                            <td class="fw-semibold">{{ $inventoryTransaction->transaction_number }}</td>
                            <td>
                                <div>{{ $inventoryTransaction->transactionType?->name ?: '-' }}</div>
                                <div class="small text-muted">{{ Str::limit($inventoryTransaction->notes, 70) }}</div>
                            </td>
                            <td>{{ $inventoryTransaction->transaction_date?->format('d M Y') }}</td>
                            <td>Rp {{ number_format((float) $inventoryTransaction->budget, 2, ',', '.') }}</td>
                            <td>Rp {{ number_format((float) $inventoryTransaction->realization, 2, ',', '.') }}</td>
                            <td>
                                @if ($inventoryTransaction->trashed())
                                    <span class="badge text-bg-danger">Terhapus</span>
                                @else
                                    <span class="badge text-bg-success">Aktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    @if (! $inventoryTransaction->trashed())
                                        <a href="{{ route('inventory-transactions.show', $inventoryTransaction) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    @if (Auth::user()->isAdmin())
                                        @if ($inventoryTransaction->trashed())
                                            <form method="POST" action="{{ route('inventory-transactions.restore', $inventoryTransaction->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('inventory-transactions.edit', $inventoryTransaction) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form method="POST" action="{{ route('inventory-transactions.destroy', $inventoryTransaction) }}" onsubmit="return confirm('Hapus transaksi inventory ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Data transaksi inventory belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inventoryTransactions->hasPages())
            <div class="card-footer bg-white">
                {{ $inventoryTransactions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
