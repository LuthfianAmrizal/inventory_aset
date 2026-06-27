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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_type_id')->constrained()->restrictOnDelete();
            $table->string('transaction_number', 50)->unique();
            $table->decimal('budget', 15, 2)->default(0);
            $table->decimal('realization', 15, 2)->default(0);
            $table->date('transaction_date')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['transaction_type_id', 'transaction_date'], 'inv_trans_type_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
