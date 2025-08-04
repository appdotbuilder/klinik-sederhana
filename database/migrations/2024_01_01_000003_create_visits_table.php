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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->datetime('visit_date')->comment('Date and time of visit');
            $table->text('chief_complaint')->nullable()->comment('Keluhan utama');
            $table->text('diagnosis')->nullable();
            $table->text('treatment')->nullable()->comment('Tindakan');
            $table->text('notes')->nullable()->comment('Catatan dokter');
            $table->text('follow_up_plan')->nullable()->comment('Rencana follow-up');
            $table->enum('status', ['menunggu', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('patient_id');
            $table->index('visit_date');
            $table->index('status');
            $table->index(['patient_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};