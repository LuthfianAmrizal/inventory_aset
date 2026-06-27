<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Rooms</h1>
                <div class="text-muted">Master data ruangan yang terhubung dengan gedung.</div>
            </div>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('rooms.create') }}" class="btn btn-primary">
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
            <form method="GET" action="{{ route('rooms.index') }}" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Cari nama ruangan">
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
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
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
                        <th>Nama Ruangan</th>
                        <th>Gedung</th>
                        <th>Lantai</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="fw-semibold">{{ $room->code }}</td>
                            <td>
                                <div>{{ $room->name }}</div>
                                <div class="small text-muted">{{ Str::limit($room->description, 70) }}</div>
                            </td>
                            <td>{{ $room->building?->name ?: '-' }}</td>
                            <td>{{ $room->floor ?: '-' }}</td>
                            <td>
                                @if ($room->trashed())
                                    <span class="badge text-bg-danger">Terhapus</span>
                                @elseif ($room->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group">
                                    @if (! $room->trashed())
                                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endif

                                    @if (Auth::user()->isAdmin())
                                        @if ($room->trashed())
                                            <form method="POST" action="{{ route('rooms.restore', $room->id) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form method="POST" action="{{ route('rooms.destroy', $room) }}" onsubmit="return confirm('Hapus ruangan ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Data ruangan belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rooms->hasPages())
            <div class="card-footer bg-white">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
