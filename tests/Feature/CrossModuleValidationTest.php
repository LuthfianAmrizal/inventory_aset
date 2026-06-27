<?php

namespace Tests\Feature;

use App\Models\Building;
use App\Models\Room;
use App\Models\ItemType;
use App\Models\Item;
use App\Models\TransactionType;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrossModuleValidationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_room_creation_with_inactive_building_fails_validation(): void
    {
        $inactiveBuilding = Building::create([
            'code' => 'BLDG-INACTIVE',
            'name' => 'Gedung Nonaktif',
            'location' => 'Lokasi',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('rooms.store'), [
                'building_id' => $inactiveBuilding->id,
                'code' => 'ROOM-01',
                'name' => 'Ruangan Baru',
                'is_active' => true,
            ]);

        $response->assertSessionHasErrors(['building_id']);
        $this->assertDatabaseMissing('rooms', ['code' => 'ROOM-01']);
    }

    public function test_room_creation_with_active_building_succeeds(): void
    {
        $activeBuilding = Building::create([
            'code' => 'BLDG-ACTIVE',
            'name' => 'Gedung Aktif',
            'location' => 'Lokasi',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('rooms.store'), [
                'building_id' => $activeBuilding->id,
                'code' => 'ROOM-02',
                'name' => 'Ruangan Baru Aktif',
                'is_active' => true,
            ]);

        $response->assertRedirect(route('rooms.index'));
        $this->assertDatabaseHas('rooms', ['code' => 'ROOM-02']);
    }

    public function test_deleting_building_with_rooms_is_prevented(): void
    {
        $building = Building::create([
            'code' => 'BLDG-A',
            'name' => 'Gedung A',
            'location' => 'Lokasi',
            'is_active' => true,
        ]);

        Room::create([
            'building_id' => $building->id,
            'code' => 'ROOM-A',
            'name' => 'Ruang A',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('buildings.destroy', $building));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Gedung tidak dapat dihapus karena masih memiliki ruangan.');
        $this->assertDatabaseHas('buildings', ['id' => $building->id, 'deleted_at' => null]);
    }

    public function test_deleting_item_type_with_items_is_prevented(): void
    {
        $itemType = ItemType::create([
            'code' => 'ELK',
            'name' => 'Elektronik',
            'is_active' => true,
        ]);

        Item::create([
            'item_type_id' => $itemType->id,
            'code' => 'ITEM-A',
            'name' => 'Barang A',
            'unit' => 'pcs',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('item-types.destroy', $itemType));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Jenis barang tidak dapat dihapus karena masih memiliki barang.');
        $this->assertDatabaseHas('item_types', ['id' => $itemType->id, 'deleted_at' => null]);
    }

    public function test_deleting_transaction_type_with_transactions_is_prevented(): void
    {
        $transactionType = TransactionType::create([
            'code' => 'TRX-IN',
            'name' => 'Pembelian',
            'direction' => 'in',
            'is_active' => true,
        ]);

        InventoryTransaction::create([
            'transaction_type_id' => $transactionType->id,
            'transaction_number' => 'INV-TRX-20260626-0001',
            'budget' => 100000,
            'realization' => 90000,
            'transaction_date' => '2026-06-26',
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('transaction-types.destroy', $transactionType));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tipe transaksi tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        $this->assertDatabaseHas('transaction_types', ['id' => $transactionType->id, 'deleted_at' => null]);
    }
}
