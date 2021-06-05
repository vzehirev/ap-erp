<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePartnerIdFromSortedMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sorted_materials', function (Blueprint $table) {
            $table->dropForeign('sorted_materials_partner_id_foreign');
            $table->dropColumn('partner_id');
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
            $table->foreignId('partner_id')->constrained();
        });
    }
}
