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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->string('dosage')->comment('Dosis obat');
            $table->string('frequency')->comment('Frekuensi minum obat');
            $table->string('duration')->comment('Durasi pengobatan');
            $table->text('instructions')->nullable()->comment('Instruksi khusus');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('prescription_id');
            $table->index('inventory_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};