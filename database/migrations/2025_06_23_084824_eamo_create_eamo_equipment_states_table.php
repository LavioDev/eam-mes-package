<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eamo_equipment_states', function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('equipment_id', 36)->unique();
            $table->string('state')->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('eamo_equipment')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eamo_equipment_states');
    }
};
