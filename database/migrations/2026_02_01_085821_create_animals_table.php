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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('animal_id')->unique();
            $table->string('breed_type');
            $table->date('date');
            $table->decimal('milk_collected', 8, 2)->nullable();
            $table->enum('health_condition', ['healthy', 'need_attention', 'sick'])->default('healthy');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('animal_id');
            $table->index('breed_type');
            $table->index('health_condition');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
