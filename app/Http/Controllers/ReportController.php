<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\TransactionType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $reportType = $request->string('report_type', 'inventory')->toString();
        $startDate = $request->string('start_date', now()->startOfMonth()->toDateString())->toString();
        $endDate = $request->string('end_date', now()->toDateString())->toString();
        $status = $request->string('status', 'all')->toString();
        $transactionTypeId = $request->integer('transaction_type_id');

        $inventories = collect();
        $transactions = collect();

        if ($reportType === 'inventory') {
            $inventories = Inventory::query()
                ->with('item')
                ->when($status !== 'all', fn ($query) => $query->where('status', $status))
                ->latest()
                ->get();
        } else {
            $transactions = InventoryTransaction::query()
                ->with('transactionType')
                ->when($startDate !== '', fn ($query) => $query->whereDate('transaction_date', '>=', $startDate))
                ->when($endDate !== '', fn ($query) => $query->whereDate('transaction_date', '<=', $endDate))
                ->when($transactionTypeId !== 0, fn ($query) => $query->where('transaction_type_id', $transactionTypeId))
                ->latest('transaction_date')
                ->get();
        }

        $transactionTypes = TransactionType::where('is_active', true)->orderBy('name')->get();

        return view('reports.index', compact(
            'reportType', 'startDate', 'endDate', 'status', 'transactionTypeId',
            'inventories', 'transactions', 'transactionTypes'
        ));
    }
}
