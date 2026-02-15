<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disease_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->unsignedBigInteger('animal_id')->index();
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->date('onset_date')->required();
            $table->date('diagnosis_date')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe'])->default('moderate');
            $table->boolean('isolation_status')->default(false);
            $table->enum('outcome', ['recovered', 'chronic', 'deceased', 'culled'])->nullable();
            $table->date('outcome_date')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('disease_outbreaks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->date('start_date')->required();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->text('biosecurity_protocols')->nullable();
            $table->enum('status', ['active', 'contained', 'resolved'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('outbreak_affected_animals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outbreak_id')->constrained()->onDelete('cascade');
            $table->foreignId('disease_case_id')->constrained()->onDelete('cascade');
            $table->date('quarantine_start')->nullable();
            $table->date('quarantine_end')->nullable();
            $table->enum('status', ['quarantined', 'released', 'deceased', 'culled'])->default('quarantined');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbreak_affected_animals');
        Schema::dropIfExists('disease_outbreaks');
        Schema::dropIfExists('disease_cases');
    }
};
