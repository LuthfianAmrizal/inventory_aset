<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'all')->toString();

        $inventories = Inventory::query()
            ->with('item')
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status !== 'all' && $status !== 'trashed', fn ($query) => $query->where('status', $status))
            ->when($status === 'all', fn ($query) => $query)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('barcode', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('item', function ($query) use ($search) {
                            $query->where('code', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('inventories.index', compact('inventories', 'search', 'status'));
    }

    public function create(): View
    {
        return view('inventories.create', [
            'inventory' => new Inventory([
                'qty' => 0,
                'price' => 0,
                'status' => 'available',
            ]),
            'items' => $this->itemOptions(),
        ]);
    }

    public function store(StoreInventoryRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('inventories', 'public');
            }
            if (empty($data['barcode'])) {
                $item = Item::findOrFail($data['item_id']);
                $data['barcode'] = strtoupper($item->code) . '-' . time() . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            Inventory::create($data);
        });

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Stok inventory berhasil ditambahkan.');
    }

    public function show(Inventory $inventory): View
    {
        $inventory->load('item');

        return view('inventories.show', compact('inventory'));
    }

    public function edit(Inventory $inventory): View
    {
        return view('inventories.edit', [
            'inventory' => $inventory,
            'items' => $this->itemOptions($inventory->item_id),
        ]);
    }

    public function update(UpdateInventoryRequest $request, Inventory $inventory): RedirectResponse
    {
        DB::transaction(function () use ($request, $inventory) {
            $data = $request->validated();
            if ($request->hasFile('photo')) {
                if ($inventory->photo) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($inventory->photo);
                }
                $data['photo'] = $request->file('photo')->store('inventories', 'public');
            }
            if (empty($data['barcode'])) {
                $item = Item::findOrFail($data['item_id']);
                $data['barcode'] = strtoupper($item->code) . '-' . time() . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            }
            $inventory->update($data);
        });

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Stok inventory berhasil diperbarui.');
    }

    public function destroy(Inventory $inventory): RedirectResponse
    {
        $this->authorizeAdmin();

        DB::transaction(fn () => $inventory->delete());

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Data inventory berhasil dihapus.');
    }

    public function restore(int $inventory): RedirectResponse
    {
        $this->authorizeAdmin();

        DB::transaction(function () use ($inventory) {
            $inventory = Inventory::onlyTrashed()->findOrFail($inventory);
            $inventory->restore();
        });

        return redirect()
            ->route('inventories.index', ['status' => 'trashed'])
            ->with('success', 'Data inventory berhasil dipulihkan.');
    }

    private function itemOptions(?int $selectedItemId = null)
    {
        return Item::query()
            ->where(function ($query) use ($selectedItemId) {
                $query->where('is_active', true)
                    ->when($selectedItemId, fn ($query) => $query->orWhereKey($selectedItemId));
            })
            ->orderBy('name')
            ->get();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(request()->user()?->isAdmin(), 403);
    }
}
