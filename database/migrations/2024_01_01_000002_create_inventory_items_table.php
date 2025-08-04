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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->comment('Product/medicine code');
            $table->enum('type', ['obat', 'barang_lain', 'alat_medis'])->comment('Item category');
            $table->text('description')->nullable();
            $table->string('unit')->comment('Unit of measurement (tablet, botol, dll)');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->comment('Selling price');
            $table->integer('minimal_alert')->default(10)->comment('Minimum stock alert threshold');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('code');
            $table->index('name');
            $table->index('type');
            $table->index('stock');
            $table->index(['stock', 'minimal_alert']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};