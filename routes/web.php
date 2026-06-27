<?php

use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TransactionTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', function () {
    $stats = [
        'total_item_types' => \App\Models\ItemType::count(),
        'total_items' => \App\Models\Item::count(),
        'total_buildings' => \App\Models\Building::count(),
        'total_rooms' => \App\Models\Room::count(),
        'total_inventory_qty' => \App\Models\Inventory::sum('qty'),
        'total_inventory_value' => \App\Models\Inventory::selectRaw('SUM(price * qty) as total')->value('total') ?: 0,
        'total_transactions' => \App\Models\InventoryTransaction::count(),
        'total_budget' => \App\Models\InventoryTransaction::sum('budget'),
        'total_realization' => \App\Models\InventoryTransaction::sum('realization'),
    ];

    $recentTransactions = \App\Models\InventoryTransaction::with('transactionType')
        ->latest()
        ->take(5)
        ->get();

    $recentInventories = \App\Models\Inventory::with('item')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard', compact('stats', 'recentTransactions', 'recentInventories'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('item-types/{itemType}/restore', [ItemTypeController::class, 'restore'])
        ->middleware('role:admin')
        ->name('item-types.restore');

    Route::resource('item-types', ItemTypeController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('item-types', ItemTypeController::class)
        ->only(['index', 'show']);

    Route::post('buildings/{building}/restore', [BuildingController::class, 'restore'])
        ->middleware('role:admin')
        ->name('buildings.restore');

    Route::resource('buildings', BuildingController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('buildings', BuildingController::class)
        ->only(['index', 'show']);

    Route::post('rooms/{room}/restore', [RoomController::class, 'restore'])
        ->middleware('role:admin')
        ->name('rooms.restore');

    Route::resource('rooms', RoomController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('rooms', RoomController::class)
        ->only(['index', 'show']);

    Route::post('transaction-types/{transactionType}/restore', [TransactionTypeController::class, 'restore'])
        ->middleware('role:admin')
        ->name('transaction-types.restore');

    Route::resource('transaction-types', TransactionTypeController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('transaction-types', TransactionTypeController::class)
        ->only(['index', 'show']);

    Route::post('inventories/{inventory}/restore', [InventoryController::class, 'restore'])
        ->middleware('role:admin')
        ->name('inventories.restore');

    Route::resource('inventories', InventoryController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('inventories', InventoryController::class)
        ->only(['index', 'show']);

    Route::post('inventory-transactions/{inventoryTransaction}/restore', [InventoryTransactionController::class, 'restore'])
        ->middleware('role:admin')
        ->name('inventory-transactions.restore');

    Route::resource('inventory-transactions', InventoryTransactionController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin');

    Route::resource('inventory-transactions', InventoryTransactionController::class)
        ->only(['index', 'show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
