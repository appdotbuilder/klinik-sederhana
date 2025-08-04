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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id')->unique()->comment('Unique patient ID number');
            $table->string('name');
            $table->date('birth_date');
            $table->enum('gender', ['L', 'P'])->comment('L=Laki-laki, P=Perempuan');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('allergies')->nullable()->comment('List of known allergies');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('patient_id');
            $table->index('name');
            $table->index(['name', 'birth_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};