<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreItemTypeRequest;
use App\Http\Requests\UpdateItemTypeRequest;
use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItemTypeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'active')->toString();

        $itemTypes = ItemType::query()
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($status === 'all', fn ($query) => $query->withTrashed())
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('item-types.index', compact('itemTypes', 'search', 'status'));
    }

    public function create(): View
    {
        return view('item-types.create', [
            'itemType' => new ItemType(['is_active' => true]),
        ]);
    }

    public function store(StoreItemTypeRequest $request): RedirectResponse
    {
        ItemType::create($this->validatedPayload($request));

        return redirect()
            ->route('item-types.index')
            ->with('success', 'Jenis barang berhasil ditambahkan.');
    }

    public function show(ItemType $itemType): View
    {
        return view('item-types.show', compact('itemType'));
    }

    public function edit(ItemType $itemType): View
    {
        return view('item-types.edit', compact('itemType'));
    }

    public function update(UpdateItemTypeRequest $request, ItemType $itemType): RedirectResponse
    {
        $itemType->update($this->validatedPayload($request));

        return redirect()
            ->route('item-types.index')
            ->with('success', 'Jenis barang berhasil diperbarui.');
    }

    public function destroy(ItemType $itemType): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($itemType->items()->exists()) {
            return redirect()
                ->route('item-types.index')
                ->with('error', 'Jenis barang tidak dapat dihapus karena masih memiliki barang.');
        }

        $itemType->delete();

        return redirect()
            ->route('item-types.index')
            ->with('success', 'Jenis barang berhasil dihapus.');
    }

    public function restore(int $itemType): RedirectResponse
    {
        $this->authorizeAdmin();

        $itemType = ItemType::onlyTrashed()->findOrFail($itemType);
        $itemType->restore();

        return redirect()
            ->route('item-types.index', ['status' => 'trashed'])
            ->with('success', 'Jenis barang berhasil dipulihkan.');
    }

    private function validatedPayload(StoreItemTypeRequest|UpdateItemTypeRequest $request): array
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
