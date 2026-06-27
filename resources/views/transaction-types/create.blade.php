<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">Tambah Tipe Transaksi</h1>
                <div class="text-muted">Buat master tipe transaksi inventory.</div>
            </div>
            <a href="{{ route('transaction-types.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="card stat-card">
        <div class="card-body">
            <form method="POST" action="{{ route('transaction-types.store') }}">
                @include('transaction-types._form')
            </form>
        </div>
    </div>
</x-app-layout>
