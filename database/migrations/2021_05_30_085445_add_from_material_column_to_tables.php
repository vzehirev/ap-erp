<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFromMaterialColumnToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sorted_materials', function (Blueprint $table) {
            $table->foreignId('from_material_id')->nullable()->constrained('materials');
            $table->foreignId('to_material_id')->nullable()->constrained('materials');
        });

        Schema::table('ground_materials', function (Blueprint $table) {
            $table->foreignId('from_material_id')->nullable()->constrained('materials');

            $table->dropForeign('ground_materials_material_id_foreign');
            $table->renameColumn('material_id', 'to_material_id');
            $table->foreign('to_material_id')->references('id')->on('materials');
        });

        Schema::table('granular_materials', function (Blueprint $table) {
            $table->foreignId('from_material_id')->nullable()->constrained('materials');

            $table->dropForeign('granular_materials_material_id_foreign');
            $table->renameColumn('material_id', 'to_material_id');
            $table->foreign('to_material_id')->references('id')->on('materials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sorted_materials', function (Blueprint $table) {
            $table->dropForeign('sorted_materials_from_material_id_foreign');
            $table->dropColumn('from_material_id');

            $table->dropForeign('sorted_materials_to_material_id_foreign');
            $table->dropColumn('to_material_id');
        });

        Schema::table('ground_materials', function (Blueprint $table) {
            $table->dropForeign('ground_materials_from_material_id_foreign');
            $table->dropColumn('from_material_id');

            $table->dropForeign('ground_materials_to_material_id_foreign');
            $table->renameColumn('to_material_id', 'material_id');
            $table->foreign('material_id')->references('id')->on('materials');
        });

        Schema::table('granular_materials', function (Blueprint $table) {
            $table->dropForeign('granular_materials_from_material_id_foreign');
            $table->dropColumn('from_material_id');

            $table->dropForeign('granular_materials_to_material_id_foreign');
            $table->renameColumn('to_material_id', 'material_id');
            $table->foreign('material_id')->references('id')->on('materials');
        });
    }
}
