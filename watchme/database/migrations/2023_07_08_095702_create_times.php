<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('times',function (Blueprint $table){
            $table->id();
            $table->string('comment')->nullable(true)
                ->comment('Kommentar in dem man angeben kann was man in der Zeit erledigt hat');
            $table->timestamps();
            $table->integer('duration')->nullable(false);
            $table->unsignedBigInteger('project_id');
            $table->foreign('project_id')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};
