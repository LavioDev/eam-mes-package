<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eamo_checklist_sessions', function (Blueprint $table) {
            $table->string('id', 36)->primary();
            $table->string('equipment_id', 36);
            $table->datetime('session_date');
            $table->string('created_by', 36);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eamo_checklist_sessions');
    }
};
