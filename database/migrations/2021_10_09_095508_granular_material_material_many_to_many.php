<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GranularMaterialMaterialManyToMany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('granular_materials', function (Blueprint $table) {
            $table->dropForeign('granular_materials_from_material_id_foreign');
            $table->dropColumn('from_material_id');
        });

        Schema::create('granular_material_from_material', function (Blueprint $table) {
            $table->foreignId('granular_material_id')->constrained();
            $table->foreignId('from_material_id')->references('id')->on('materials')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('granular_materials', function (Blueprint $table) {
            $table->foreignId('from_material_id')->references('id')->on('materials')->nullable()->constrained();
        });

        Schema::dropIfExists('granular_material_from_material');
    }
}
