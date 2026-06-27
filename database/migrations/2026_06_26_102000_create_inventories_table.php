<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('qty')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->string('barcode', 100)->nullable()->unique();
            $table->date('expired_date')->nullable();
            $table->string('status', 50)->default('available')->index();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['item_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
