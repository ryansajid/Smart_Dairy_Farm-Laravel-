<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('treatment_number')->unique();
            $table->unsignedBigInteger('animal_id')->required();
            $table->unsignedBigInteger('disease_case_id')->nullable();
            $table->dateTime('treatment_date')->required();
            $table->enum('treatment_type', ['medical', 'surgical', 'physical', 'supportive'])->default('medical');
            $table->string('diagnosis')->nullable();
            $table->text('treatment_description')->nullable();
            $table->string('prescribed_by')->nullable();
            $table->string('administered_by')->nullable();
            $table->enum('response', ['improving', 'no_change', 'worsening', 'resolved'])->nullable();
            $table->dateTime('next_treatment_date')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('animal_id');
            $table->index('treatment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
