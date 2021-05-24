<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGranularMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('granular_materials', function (Blueprint $table) {
            $table->id();
            $table->date('granular_on');
            $table->foreignId('worker_id')->constrained();
            $table->foreignId('material_id')->constrained();
            $table->double('quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('granular_materials');
    }
}
