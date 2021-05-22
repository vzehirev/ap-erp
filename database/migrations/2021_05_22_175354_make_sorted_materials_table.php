<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSortedMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sorted_materials', function (Blueprint $table) {
            $table->id();
            $table->date('sorted_on');
            $table->foreignId('partner_id')->constrained();
            $table->foreignId('worker_id')->constrained();
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
        Schema::dropIfExists('sorted_materials');
    }
}
