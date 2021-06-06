<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGranularMaterialIdColumnToWastedMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wasted_materials', function (Blueprint $table) {
            $table->foreignId('granular_material_id')->nullable()->constrained();
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
            $table->dropForeign('wasted_materials_granular_material_id_foreign');
        });
    }
}
