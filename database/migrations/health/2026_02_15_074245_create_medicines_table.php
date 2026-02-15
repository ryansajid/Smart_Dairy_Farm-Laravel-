<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('generic_name')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('supplier')->nullable();
            $table->enum('medicine_form', ['tablet', 'capsule', 'injection', 'liquid', 'powder', 'ointment', 'spray'])->default('tablet');
            $table->string('strength')->nullable();
            $table->text('storage_requirements')->nullable();
            $table->boolean('expiry_tracking')->default(true);
            $table->integer('reorder_level')->default(10);
            $table->integer('reorder_qty')->default(50);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->integer('current_stock')->default(0);
            $table->string('stock_location')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
