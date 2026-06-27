<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 no-print">
            <div>
                <h1 class="page-title">Laporan</h1>
                <div class="text-muted">Cetak laporan stok inventory dan transaksi barang</div>
            </div>
        </div>
    </x-slot>

    <!-- Filter Card (Hidden in Print) -->
    <div class="card stat-card mb-4 no-print border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('reports.index') }}" id="reportForm">
                <div class="row g-3 align-items-end">
                    <!-- Report Type Selector -->
                    <div class="col-md-3">
                        <label for="report_type" class="form-label fw-semibold">Pilih Jenis Laporan</label>
                        <select id="report_type" name="report_type" class="form-select" onchange="toggleFilterFields(this.value)">
                            <option value="inventory" @selected($reportType === 'inventory')>Laporan Stok Barang</option>
                            <option value="transaction" @selected($reportType === 'transaction')>Laporan Transaksi</option>
                        </select>
                    </div>

                    <!-- Inventory Specific Filters -->
                    <div class="col-md-3 filter-inventory" style="display: {{ $reportType === 'inventory' ? 'block' : 'none' }}">
                        <label for="status" class="form-label fw-semibold">Filter Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="all" @selected($status === 'all')>Semua Status</option>
                            <option value="available" @selected($status === 'available')>Available</option>
                            <option value="reserved" @selected($status === 'reserved')>Reserved</option>
                            <option value="damaged" @selected($status === 'damaged')>Damaged</option>
                            <option value="lost" @selected($status === 'lost')>Lost</option>
                            <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                        </select>
                    </div>

                    <!-- Transaction Specific Filters (Date Range) -->
                    <div class="col-md-3 filter-transaction" style="display: {{ $reportType === 'transaction' ? 'block' : 'none' }}">
                        <label for="start_date" class="form-label fw-semibold">Dari Tanggal</label>
                        <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="form-control">
                    </div>
                    <div class="col-md-3 filter-transaction" style="display: {{ $reportType === 'transaction' ? 'block' : 'none' }}">
                        <label for="end_date" class="form-label fw-semibold">Sampai Tanggal</label>
                        <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="form-control">
                    </div>
                    <div class="col-md-3 filter-transaction" style="display: {{ $reportType === 'transaction' ? 'block' : 'none' }}">
                        <label for="transaction_type_id" class="form-label fw-semibold">Tipe Transaksi</label>
                        <select id="transaction_type_id" name="transaction_type_id" class="form-select">
                            <option value="0" @selected($transactionTypeId === 0)>Semua Tipe</option>
                            @foreach ($transactionTypes as $type)
                                <option value="{{ $type->id }}" @selected($transactionTypeId === $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-filter me-1"></i> Tampilkan
                        </button>
                        <button type="button" onclick="window.print()" class="btn btn-outline-success px-4 ms-2">
                            <i class="bi bi-printer me-1"></i> Cetak PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div class="print-area card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <!-- Print Header (Only Visible in Print) -->
            <div class="print-header d-none text-center mb-4 pb-3 border-bottom">
                <h3 class="fw-bold mb-1">SISTEM INFORMASI INVENTORY ASET</h3>
                <div class="text-uppercase fw-semibold tracking-wider">
                    @if ($reportType === 'inventory')
                        LAPORAN STOK INVENTORY BARANG
                    @else
                        LAPORAN RIWAYAT TRANSAKSI INVENTORY
                    @endif
                </div>
                <div class="text-muted small mt-1">
                    @if ($reportType === 'inventory')
                        Status Barang: {{ $status === 'all' ? 'Semua Status' : Str::headline($status) }}
                    @else
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    @endif
                </div>
                <div class="text-muted small">Dicetak oleh: {{ Auth::user()->name }} ({{ Str::headline(Auth::user()->role) }}) pada {{ now()->format('d M Y H:i') }}</div>
            </div>

            <!-- Report Table -->
            @if ($reportType === 'inventory')
                <!-- Inventory Report Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total Nilai</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalQty = 0; $totalValue = 0; @endphp
                            @forelse ($inventories as $index => $inv)
                                @php
                                    $totalQty += $inv->qty;
                                    $totalValue += $inv->qty * $inv->price;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $inv->item?->code ?: '-' }}</td>
                                    <td>{{ $inv->item?->name ?: '-' }}</td>
                                    <td class="text-end">{{ number_format($inv->qty) }}</td>
                                    <td class="text-end">Rp {{ number_format($inv->price, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($inv->qty * $inv->price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge text-bg-light border text-dark">{{ Str::headline($inv->status) }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $inv->description ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($inventories->isNotEmpty())
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="3" class="text-end">TOTAL</td>
                                    <td class="text-end">{{ number_format($totalQty) }}</td>
                                    <td></td>
                                    <td class="text-end text-success">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @else
                <!-- Transaction Report Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;" class="text-center">No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jenis Transaksi</th>
                                <th>Arah</th>
                                <th class="text-end">Budget</th>
                                <th class="text-end">Realisasi</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalBudget = 0; $totalRealization = 0; @endphp
                            @forelse ($transactions as $index => $trx)
                                @php
                                    $totalBudget += $trx->budget;
                                    $totalRealization += $trx->realization;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $trx->transaction_number }}</td>
                                    <td>{{ $trx->transaction_date?->format('d M Y') }}</td>
                                    <td>{{ $trx->transactionType?->name ?: '-' }}</td>
                                    <td>
                                        <span class="badge text-bg-light border text-dark">{{ Str::headline($trx->transactionType?->direction) }}</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($trx->budget, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($trx->realization, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ $trx->notes ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Data tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if ($transactions->isNotEmpty())
                            <tfoot class="table-light fw-bold">
                                <tr>
                                    <td colspan="5" class="text-end">TOTAL</td>
                                    <td class="text-end text-blue">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                                    <td class="text-end text-success">Rp {{ number_format($totalRealization, 0, ',', '.') }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript to toggle forms based on selected report type -->
    <script>
        function toggleFilterFields(type) {
            const invFields = document.querySelectorAll('.filter-inventory');
            const trFields = document.querySelectorAll('.filter-transaction');
            
            if (type === 'inventory') {
                invFields.forEach(f => f.style.display = 'block');
                trFields.forEach(f => f.style.display = 'none');
            } else {
                invFields.forEach(f => f.style.display = 'none');
                trFields.forEach(f => f.style.display = 'block');
            }
        }
    </script>

    <!-- Custom Print CSS Styling -->
    <style>
        @media print {
            body {
                background-color: white !important;
                color: black !important;
                font-size: 12px;
            }
            .no-print {
                display: none !important;
            }
            .print-header {
                display: block !important;
            }
            .print-area {
                border: 0 !important;
                box-shadow: none !important;
            }
            .table-responsive {
                overflow: visible !important;
            }
            nav, header, footer {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</x-app-layout>
