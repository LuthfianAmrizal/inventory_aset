<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Detail Ruangan</h1>
                <div class="text-muted">{{ $room->code }}</div>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                @endif
                <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Kode</dt>
                <dd class="col-sm-9">{{ $room->code }}</dd>

                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9">{{ $room->name }}</dd>

                <dt class="col-sm-3">Gedung</dt>
                <dd class="col-sm-9">{{ $room->building?->name ?: '-' }}</dd>

                <dt class="col-sm-3">Lantai</dt>
                <dd class="col-sm-9">{{ $room->floor ?: '-' }}</dd>

                <dt class="col-sm-3">Kapasitas</dt>
                <dd class="col-sm-9">{{ $room->capacity ?? '-' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    @if ($room->is_active)
                        <span class="badge text-bg-success">Aktif</span>
                    @else
                        <span class="badge text-bg-secondary">Nonaktif</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Deskripsi</dt>
                <dd class="col-sm-9">{{ $room->description ?: '-' }}</dd>

                <dt class="col-sm-3">Dibuat</dt>
                <dd class="col-sm-9">{{ $room->created_at?->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Diperbarui</dt>
                <dd class="col-sm-9">{{ $room->updated_at?->format('d M Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
