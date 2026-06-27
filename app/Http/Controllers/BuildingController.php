<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuildingRequest;
use App\Http\Requests\UpdateBuildingRequest;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BuildingController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'active')->toString();

        $buildings = Building::query()
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($status === 'all', fn ($query) => $query->withTrashed())
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('buildings.index', compact('buildings', 'search', 'status'));
    }

    public function create(): View
    {
        return view('buildings.create', [
            'building' => new Building(['is_active' => true]),
        ]);
    }

    public function store(StoreBuildingRequest $request): RedirectResponse
    {
        Building::create($this->validatedPayload($request));

        return redirect()
            ->route('buildings.index')
            ->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function show(Building $building): View
    {
        return view('buildings.show', compact('building'));
    }

    public function edit(Building $building): View
    {
        return view('buildings.edit', compact('building'));
    }

    public function update(UpdateBuildingRequest $request, Building $building): RedirectResponse
    {
        $building->update($this->validatedPayload($request));

        return redirect()
            ->route('buildings.index')
            ->with('success', 'Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building): RedirectResponse
    {
        $this->authorizeAdmin();

        if ($building->rooms()->exists()) {
            return redirect()
                ->route('buildings.index')
                ->with('error', 'Gedung tidak dapat dihapus karena masih memiliki ruangan.');
        }

        $building->delete();

        return redirect()
            ->route('buildings.index')
            ->with('success', 'Gedung berhasil dihapus.');
    }

    public function restore(int $building): RedirectResponse
    {
        $this->authorizeAdmin();

        $building = Building::onlyTrashed()->findOrFail($building);
        $building->restore();

        return redirect()
            ->route('buildings.index', ['status' => 'trashed'])
            ->with('success', 'Gedung berhasil dipulihkan.');
    }

    private function validatedPayload(StoreBuildingRequest|UpdateBuildingRequest $request): array
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
