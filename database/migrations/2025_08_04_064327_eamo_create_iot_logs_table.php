<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Get the connection name for the migration
     */
    public function getConnection(): ?string
    {
        return 'pgsql_log';
    }

    public function up(): void
    {
        // check if exist, return
        if (Schema::connection('pgsql_log')->hasTable('iot_logs')) {
            return;
        }
        Schema::connection('pgsql_log')->create('iot_logs', function (Blueprint $table) {
            // Primary key - bigserial
            $table->id(); // This creates bigserial in PostgreSQL

            // Timestamp - timestamptz
            $table->timestampTz('ts')->nullable(false);

            // Type - string, not enum in DB
            $table->string('type')->nullable(false);

            // JSONB data
            $table->jsonb('data')->nullable();

            // Equipment and Work Center IDs
            $table->string('equipment_id')->nullable(false);
            $table->string('work_center_id')->nullable(false);
            $table->string('work_order_id')->nullable();

            // Index on (equipment_id, ts)
            $table->index(['equipment_id', 'ts']);
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql_log')->dropIfExists('iot_logs');
    }
};
