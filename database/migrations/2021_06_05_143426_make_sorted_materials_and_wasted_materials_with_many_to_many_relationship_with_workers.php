<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSortedMaterialsAndWastedMaterialsWithManyToManyRelationshipWithWorkers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sorted_materials', function (Blueprint $table) {
            $table->dropForeign('sorted_materials_worker_id_foreign');
            $table->dropColumn('worker_id');
        });

        Schema::table('wasted_materials', function (Blueprint $table) {
            $table->dropForeign('wasted_materials_worker_id_foreign');
            $table->dropColumn('worker_id');
        });

        Schema::create('sorted_material_worker', function (Blueprint $table) {
            $table->foreignId('sorted_material_id')->constrained();
            $table->foreignId('worker_id')->constrained();
        });

        Schema::create('wasted_material_worker', function (Blueprint $table) {
            $table->foreignId('wasted_material_id')->constrained();
            $table->foreignId('worker_id')->constrained();
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
            $table->foreignId('worker_id')->nullable()->constrained();
        });

        Schema::table('wasted_materials', function (Blueprint $table) {
            $table->foreignId('worker_id')->nullable()->constrained();
        });

        Schema::dropIfExists('sorted_material_worker');
        Schema::dropIfExists('wasted_material_worker');
    }
}
