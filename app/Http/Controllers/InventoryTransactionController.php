<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Http\Requests\UpdateInventoryTransactionRequest;
use App\Models\InventoryTransaction;
use App\Models\TransactionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'active')->toString();

        $inventoryTransactions = InventoryTransaction::query()
            ->with('transactionType')
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status === 'all', fn ($query) => $query->withTrashed())
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('transaction_number', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('transactionType', function ($query) use ($search) {
                            $query->where('code', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('transaction_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('inventory-transactions.index', compact('inventoryTransactions', 'search', 'status'));
    }

    public function create(): View
    {
        return view('inventory-transactions.create', [
            'inventoryTransaction' => new InventoryTransaction([
                'budget' => 0,
                'realization' => 0,
                'transaction_date' => now()->toDateString(),
            ]),
            'transactionTypes' => $this->transactionTypeOptions(),
        ]);
    }

    public function store(StoreInventoryTransactionRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            if ($request->hasFile('evidence')) {
                $data['evidence'] = $request->file('evidence')->store('evidences', 'public');
            }
            InventoryTransaction::create(array_merge($data, [
                'transaction_number' => $this->nextTransactionNumber($request->date('transaction_date')->format('Ymd')),
            ]));
        });

        return redirect()
            ->route('inventory-transactions.index')
            ->with('success', 'Transaksi inventory berhasil ditambahkan.');
    }

    public function show(InventoryTransaction $inventoryTransaction): View
    {
        $inventoryTransaction->load('transactionType');

        return view('inventory-transactions.show', compact('inventoryTransaction'));
    }

    public function edit(InventoryTransaction $inventoryTransaction): View
    {
        return view('inventory-transactions.edit', [
            'inventoryTransaction' => $inventoryTransaction,
            'transactionTypes' => $this->transactionTypeOptions($inventoryTransaction->transaction_type_id),
        ]);
    }

    public function update(UpdateInventoryTransactionRequest $request, InventoryTransaction $inventoryTransaction): RedirectResponse
    {
        DB::transaction(function () use ($request, $inventoryTransaction) {
            $data = $request->validated();
            if ($request->hasFile('evidence')) {
                if ($inventoryTransaction->evidence) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($inventoryTransaction->evidence);
                }
                $data['evidence'] = $request->file('evidence')->store('evidences', 'public');
            }
            $inventoryTransaction->update($data);
        });

        return redirect()
            ->route('inventory-transactions.index')
            ->with('success', 'Transaksi inventory berhasil diperbarui.');
    }

    public function destroy(InventoryTransaction $inventoryTransaction): RedirectResponse
    {
        $this->authorizeAdmin();

        DB::transaction(fn () => $inventoryTransaction->delete());

        return redirect()
            ->route('inventory-transactions.index')
            ->with('success', 'Transaksi inventory berhasil dihapus.');
    }

    public function restore(int $inventoryTransaction): RedirectResponse
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($inventoryTransaction) {
            $inventoryTransaction = InventoryTransaction::onlyTrashed()->findOrFail($inventoryTransaction);
            $inventoryTransaction->restore();
        });

        return redirect()
            ->route('inventory-transactions.index', ['status' => 'trashed'])
            ->with('success', 'Transaksi inventory berhasil dipulihkan.');
    }

    private function nextTransactionNumber(string $dateSegment): string
    {
        $prefix = "INV-TRX-{$dateSegment}-";

        $lastNumber = InventoryTransaction::withTrashed()
            ->where('transaction_number', 'like', "{$prefix}%")
            ->lockForUpdate()
            ->orderByDesc('transaction_number')
            ->value('transaction_number');

        $sequence = $lastNumber
            ? ((int) substr($lastNumber, -4)) + 1
            : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }

    private function transactionTypeOptions(?int $selectedTransactionTypeId = null)
    {
        return TransactionType::query()
            ->where(function ($query) use ($selectedTransactionTypeId) {
                $query->where('is_active', true)
                    ->when($selectedTransactionTypeId, fn ($query) => $query->orWhereKey($selectedTransactionTypeId));
            })
            ->orderBy('name')
            ->get();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(request()->user()?->isAdmin(), 403);
    }
}
