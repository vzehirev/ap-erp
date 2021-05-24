<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWashedMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('washed_materials', function (Blueprint $table) {
            $table->id();
            $table->date('washed_on');
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('from_material_id')->constrained('materials');
            $table->double('quantity');
            $table->foreignId('to_material_id')->constrained('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('washed_materials');
    }
}
