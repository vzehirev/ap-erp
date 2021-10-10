<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeWastedMaterialsFromMaterialNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wasted_materials', function (Blueprint $table) {
            $table->dropForeign('wasted_materials_from_material_id_foreign');
            $table->unsignedBigInteger('from_material_id')->nullable()->change();
            $table->foreign('from_material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wasted_materials', function (Blueprint $table) {
            $table->dropForeign('wasted_materials_from_material_id_foreign');
            $table->unsignedBigInteger('from_material_id')->change();
            $table->foreign('from_material_id')->references('id')->on('materials');
        });
    }
}
