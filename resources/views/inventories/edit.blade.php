<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Update Stok Inventory</h1>
                <div class="text-muted">{{ $inventory->item?->code }} - {{ $inventory->item?->name }}</div>
            </div>
            <a href="{{ route('inventories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventories.update', $inventory) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('inventories._form')
            </form>
        </div>
    </div>
</x-app-layout>
