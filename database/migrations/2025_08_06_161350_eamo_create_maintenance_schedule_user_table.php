<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('eamo_maintenance_schedule_user', function (Blueprint $table) {
            $table->string('maintenance_schedule_id', 36);
            $table->uuid('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->primary(['maintenance_schedule_id', 'user_id']);

            $table->foreign('maintenance_schedule_id')
                ->references('id')
                ->on('eamo_maintenance_schedules')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eamo_maintenance_schedule_user');
    }
};
