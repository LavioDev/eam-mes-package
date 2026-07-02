<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('eamo_checklist_details', function (Blueprint $table) {
            $table->string('id',36)->primary();
            $table->string('checklist_id',36);
            $table->string('session_id',36);

            $table->string('description')->nullable(); 
            $table->enum('result',['pass','fail'])->nullable();
            // $table->json('image_ids')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('eamo_checklist_details');
    }
};
