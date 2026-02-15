<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->enum('disease_type', ['bacterial', 'viral', 'parasitic', 'fungal', 'metabolic', 'nutritional', 'other'])->default('bacterial');
            $table->integer('incubation_period_days')->nullable();
            $table->text('transmission_methods')->nullable();
            $table->text('clinical_signs')->nullable();
            $table->text('treatment_protocol')->nullable();
            $table->text('prevention')->nullable();
            $table->boolean('zoonotic')->default(false);
            $table->boolean('notifiable')->default(false);
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diseases');
    }
};
