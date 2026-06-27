<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionTypeRequest;
use App\Http\Requests\UpdateTransactionTypeRequest;
use App\Models\TransactionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'active')->toString();

        $transactionTypes = TransactionType::query()
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($status === 'all', fn ($query) => $query->withTrashed())
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('direction', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('transaction-types.index', compact('transactionTypes', 'search', 'status'));
    }

    public function create(): View
    {
        return view('transaction-types.create', [
            'transactionType' => new TransactionType(['is_active' => true]),
        ]);
    }

    public function store(StoreTransactionTypeRequest $request): RedirectResponse
    {
        TransactionType::create($this->validatedPayload($request));

        return redirect()
            ->route('transaction-types.index')
            ->with('success', 'Tipe transaksi berhasil ditambahkan.');
    }

    public function show(TransactionType $transactionType): View
    {
        return view('transaction-types.show', compact('transactionType'));
    }

    public function edit(TransactionType $transactionType): View
    {
        return view('transaction-types.edit', compact('transactionType'));
    }

    public function update(UpdateTransactionTypeRequest $request, TransactionType $transactionType): RedirectResponse
    {
        $transactionType->update($this->validatedPayload($request));

        return redirect()
            ->route('transaction-types.index')
            ->with('success', 'Tipe transaksi berhasil diperbarui.');
    }

    public function destroy(TransactionType $transactionType): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($transactionType->inventoryTransactions()->exists()) {
            return redirect()
                ->route('transaction-types.index')
                ->with('error', 'Tipe transaksi tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $transactionType->delete();

        return redirect()
            ->route('transaction-types.index')
            ->with('success', 'Tipe transaksi berhasil dihapus.');
    }

    public function restore(int $transactionType): RedirectResponse
    {
        $this->authorizeAdmin();

        $transactionType = TransactionType::onlyTrashed()->findOrFail($transactionType);
        $transactionType->restore();

        return redirect()
            ->route('transaction-types.index', ['status' => 'trashed'])
            ->with('success', 'Tipe transaksi berhasil dipulihkan.');
    }

    private function validatedPayload(StoreTransactionTypeRequest|UpdateTransactionTypeRequest $request): array
    {
        return array_merge($request->validated(), [
            'is_active' => $request->boolean('is_active'),
        ]);
    }

    private function authorizeAdmin(): void
    {
        abort_unless(request()->user()?->isAdmin(), 403);
    }
}
