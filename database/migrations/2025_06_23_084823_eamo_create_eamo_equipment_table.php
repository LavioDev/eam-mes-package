<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eamo_equipment', function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('code', 32)->unique();
            $table->string('process_id', 36)->nullable();
            $table->string('factory_id')->nullable();
            $table->boolean('virtual_equipment')->default(false);
            $table->string('equipment_category_id',36)->nullable();
            $table->string('image_id', 36)->nullable();
            $table->date('date_imported')->nullable();
            $table->boolean('state')->default(1);
            $table->string('name')->nullable();
            $table->string('device_id', 36)->nullable();
            $table->unsignedInteger('assigned_productivity_per_hour')->nullable()->comment('Assigned productivity per hour');
            $table->decimal('assigned_machine_productivity_person', 10, 2)->nullable()->comment('Assigned machine productivity (person)');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('equipment_category_id')->references('id')->on('eamo_equipment_categories')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eamo_equipment');
    }
};
