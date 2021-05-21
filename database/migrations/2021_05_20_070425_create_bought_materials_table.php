<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoughtMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bought_materials', function (Blueprint $table) {
            $table->id();
            $table->date('bought_on');
            $table->foreignId('partner_id')->constrained();
            $table->decimal('price');
            $table->double('quantity');
            $table->foreignId('product_id')->constrained();
            $table->string('invoice_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bought_materials');
    }
}
