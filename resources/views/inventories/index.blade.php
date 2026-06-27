<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Inventory</h1>
                <div class="text-muted">Kelola stok, harga, barcode, dan status barang.</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('inventories.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Stok
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
            <form method="GET" action="{{ route('inventories.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari kode item, nama item, barcode, atau status">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Filter Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="all" @selected($status === 'all')>Semua</option>
                        <option value="available" @selected($status === 'available')>Available</option>
                        <option value="reserved" @selected($status === 'reserved')>Reserved</option>
                        <option value="damaged" @selected($status === 'damaged')>Damaged</option>
                        <option value="lost" @selected($status === 'lost')>Lost</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                        <option value="trashed" @selected($status === 'trashed')>Terhapus</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">
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
                        <th style="width: 80px;">Foto</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Barcode</th>
                        <th>Expired</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td>
                                @if ($inventory->photo)
                                    <img src="{{ asset('storage/' . $inventory->photo) }}" alt="Foto" class="rounded border shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="bi bi-image" style="font-size: 1.2rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $inventory->item?->name ?: '-' }}</div>
                                <div class="small text-muted">{{ $inventory->item?->code ?: '-' }}</div>
                            </td>
                            <td>{{ number_format($inventory->qty) }}</td>
                            <td>Rp {{ number_format((float) $inventory->price, 2, ',', '.') }}</td>
                            <td>{{ $inventory->barcode ?: '-' }}</td>
                            <td>{{ $inventory->expired_date?->format('d M Y') ?: '-' }}</td>
                            <td>
                                @if ($inventory->trashed())
                                    <span class="badge text-bg-danger">Terhapus</span>
                                @else
                                    <span class="badge text-bg-info">{{ Str::headline($inventory->status) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    @if (! $inventory->trashed())
                                        <a href="{{ route('inventories.show', $inventory) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    @if (Auth::user()->isAdmin())
                                        @if ($inventory->trashed())
                                            <form method="POST" action="{{ route('inventories.restore', $inventory->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form method="POST" action="{{ route('inventories.destroy', $inventory) }}" onsubmit="return confirm('Hapus data inventory ini?')">
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
                            <td colspan="8" class="text-center text-muted py-4">Data inventory belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inventories->hasPages())
            <div class="card-footer bg-white">
                {{ $inventories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
