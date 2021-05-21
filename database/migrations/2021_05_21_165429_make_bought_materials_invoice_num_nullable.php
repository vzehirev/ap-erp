<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeBoughtMaterialsInvoiceNumNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bought_materials', function (Blueprint $table) {
            $table->string('invoice_num')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bought_materials', function (Blueprint $table) {
            $table->string('invoice_num')->nullable(false)->change();
        });
    }
}
