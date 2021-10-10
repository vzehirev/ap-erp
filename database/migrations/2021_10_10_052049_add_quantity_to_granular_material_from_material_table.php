<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToGranularMaterialFromMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granular_material_from_material', function (Blueprint $table) {
            $table->double('from_material_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('granular_material_from_material', function (Blueprint $table) {
            $table->dropColumn('from_material_quantity');
        });
    }
}
