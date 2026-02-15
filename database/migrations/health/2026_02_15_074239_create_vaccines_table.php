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
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('manufacturer')->nullable();
            $table->enum('vaccine_type', ['live', 'inactivated', 'recombinant', 'toxoid', 'subunit'])->default('inactivated');
            $table->enum('administration_route', ['injection', 'oral', 'intranasal', 'topical'])->default('injection');
            $table->decimal('dosage', 8, 2)->nullable();
            $table->integer('storage_temp_min')->nullable();
            $table->integer('storage_temp_max')->nullable();
            $table->integer('shelf_life_months')->nullable();
            $table->decimal('cost_per_dose', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->string('regulatory_approval_number')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
