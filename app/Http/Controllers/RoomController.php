<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $status = $request->string('status', 'active')->toString();

        $rooms = Room::query()
            ->with('building')
            ->when($status === 'trashed', fn ($query) => $query->onlyTrashed())
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->when($status === 'all', fn ($query) => $query->withTrashed())
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($search !== '', fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('rooms.index', compact('rooms', 'search', 'status'));
    }

    public function create(): View
    {
        return view('rooms.create', [
            'room' => new Room(['is_active' => true]),
            'buildings' => $this->buildingOptions(),
        ]);
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create($this->validatedPayload($request));

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function show(Room $room): View
    {
        $room->load('building');

        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room): View
    {
        return view('rooms.edit', [
            'room' => $room,
            'buildings' => $this->buildingOptions($room->building_id),
        ]);
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($this->validatedPayload($request));

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $this->authorizeAdmin();

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }

    public function restore(int $room): RedirectResponse
    {
        $this->authorizeAdmin();

        $room = Room::onlyTrashed()->findOrFail($room);
        $room->restore();

        return redirect()
            ->route('rooms.index', ['status' => 'trashed'])
            ->with('success', 'Ruangan berhasil dipulihkan.');
    }

    private function validatedPayload(StoreRoomRequest|UpdateRoomRequest $request): array
    {
        return array_merge($request->validated(), [
            'is_active' => $request->boolean('is_active'),
        ]);
    }

    private function buildingOptions(?int $selectedBuildingId = null)
    {
        return Building::query()
            ->where(function ($query) use ($selectedBuildingId) {
                $query->where('is_active', true)
                    ->when($selectedBuildingId, fn ($query) => $query->orWhereKey($selectedBuildingId));
            })
            ->orderBy('name')
            ->get();
    }

    private function authorizeAdmin(): void
    {
        abort_unless(request()->user()?->isAdmin(), 403);
    }
}
