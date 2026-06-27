<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Tambah Transaksi Inventory</h1>
                <div class="text-muted">Nomor transaksi dibuat otomatis saat disimpan.</div>
            </div>
            <a href="{{ route('inventory-transactions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory-transactions.store') }}" enctype="multipart/form-data">
                @include('inventory-transactions._form')
            </form>
        </div>
    </div>
</x-app-layout>
