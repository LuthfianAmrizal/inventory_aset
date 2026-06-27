<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Transaction Types</h1>
                <div class="text-muted">Master data tipe transaksi inventory aset.</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('transaction-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah
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
            <form method="GET" action="{{ route('transaction-types.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari kode, nama, arah, atau deskripsi">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Filter Status</label>
                    <select id="status" name="status" class="form-select">
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                        <option value="all" @selected($status === 'all')>Semua</option>
                        <option value="trashed" @selected($status === 'trashed')>Terhapus</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-fill">
                        <i class="bi bi-search me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('transaction-types.index') }}" class="btn btn-outline-secondary">
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
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Arah</th>
                        <th>Status</th>
                        <th>Diperbarui</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactionTypes as $transactionType)
                        <tr>
                            <td class="fw-semibold">{{ $transactionType->code }}</td>
                            <td>
                                <div>{{ $transactionType->name }}</div>
                                <div class="small text-muted">{{ Str::limit($transactionType->description, 70) }}</div>
                            </td>
                            <td>
                                <span class="badge text-bg-info">{{ Str::headline($transactionType->direction) }}</span>
                            </td>
                            <td>
                                @if ($transactionType->trashed())
                                    <span class="badge text-bg-danger">Terhapus</span>
                                @elseif ($transactionType->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td>{{ $transactionType->updated_at?->format('d M Y H:i') }}</td>
                            <td class="text-end">
                                <div class="btn-group">
                                    @if (! $transactionType->trashed())
                                        <a href="{{ route('transaction-types.show', $transactionType) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    @if (Auth::user()->isAdmin())
                                        @if ($transactionType->trashed())
                                            <form method="POST" action="{{ route('transaction-types.restore', $transactionType->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('transaction-types.edit', $transactionType) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form method="POST" action="{{ route('transaction-types.destroy', $transactionType) }}" onsubmit="return confirm('Hapus tipe transaksi ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Data tipe transaksi belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($transactionTypes->hasPages())
            <div class="card-footer bg-white">
                {{ $transactionTypes->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
