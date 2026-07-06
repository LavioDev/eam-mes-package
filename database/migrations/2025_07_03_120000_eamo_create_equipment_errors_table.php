<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_errors', function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('name');
            $table->text('reason')->nullable();
            $table->text('fix')->nullable();
            $table->text('protection_measures')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_errors');
    }
};
