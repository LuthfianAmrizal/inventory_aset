<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Edit Transaksi Inventory</h1>
                <div class="text-muted">{{ $inventoryTransaction->transaction_number }}</div>
            </div>
            <a href="{{ route('inventory-transactions.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory-transactions.update', $inventoryTransaction) }}" enctype="multipart/form-data">
                @method('PUT')
                @include('inventory-transactions._form')
            </form>
        </div>
    </div>
</x-app-layout>
