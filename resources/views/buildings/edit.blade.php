<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Edit Gedung</h1>
                <div class="text-muted">{{ $building->code }} - {{ $building->name }}</div>
            </div>
            <a href="{{ route('buildings.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <form method="POST" action="{{ route('buildings.update', $building) }}">
                @method('PUT')
                @include('buildings._form')
            </form>
        </div>
    </div>
</x-app-layout>
