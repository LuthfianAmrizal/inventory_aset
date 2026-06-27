<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Detail Gedung</h1>
                <div class="text-muted">{{ $building->code }}</div>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('buildings.edit', $building) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i> Edit
                    </a>
                @endif
                <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Kode</dt>
                <dd class="col-sm-9">{{ $building->code }}</dd>

                <dt class="col-sm-3">Nama</dt>
                <dd class="col-sm-9">{{ $building->name }}</dd>

                <dt class="col-sm-3">Lokasi</dt>
                <dd class="col-sm-9">{{ $building->location ?: '-' }}</dd>

                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">
                    @if ($building->is_active)
                        <span class="badge text-bg-success">Aktif</span>
                    @else
                        <span class="badge text-bg-secondary">Nonaktif</span>
                    @endif
                </dd>

                <dt class="col-sm-3">Deskripsi</dt>
                <dd class="col-sm-9">{{ $building->description ?: '-' }}</dd>

                <dt class="col-sm-3">Dibuat</dt>
                <dd class="col-sm-9">{{ $building->created_at?->format('d M Y H:i') }}</dd>

                <dt class="col-sm-3">Diperbarui</dt>
                <dd class="col-sm-9">{{ $building->updated_at?->format('d M Y H:i') }}</dd>
            </dl>
        </div>
    </div>
</x-app-layout>
