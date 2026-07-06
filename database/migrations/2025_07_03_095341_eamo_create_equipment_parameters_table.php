<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_parameters', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('code', 32)->unique();
            $table->string('equipment_id', 36)->nullable();
            $table->string('product_category_id', 36)->nullable();
            $table->string('equipment_category_id', 36)->nullable();
            $table->string('unit_id', 36)->nullable();
            $table->string('name');
            // $table->decimal('standard_min', 15, 2)->nullable();
            // $table->decimal('standard_max', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('unit_id')->references('id')->on('units')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('product_category_id')->references('id')->on('product_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('equipment_category_id')->references('id')->on('equipment_categories')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_parameters');
    }
};
