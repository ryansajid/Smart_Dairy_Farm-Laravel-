<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('animal_id')->required();
            $table->unsignedBigInteger('medicine_id')->required();
            $table->unsignedBigInteger('treatment_id')->nullable();
            $table->date('treatment_date')->required();
            $table->integer('milk_withdrawal_hours')->default(0);
            $table->integer('meat_withdrawal_days')->default(0);
            $table->dateTime('milk_withdrawal_end')->nullable();
            $table->date('meat_withdrawal_end')->nullable();
            $table->date('milk_test_date')->nullable();
            $table->enum('milk_test_result', ['pending', 'passed', 'failed'])->default('pending');
            $table->enum('status', ['active', 'completed', 'violated'])->default('active');
            $table->decimal('milk_discard_qty', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('animal_id');
            $table->index('status');
            $table->index('milk_withdrawal_end');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
