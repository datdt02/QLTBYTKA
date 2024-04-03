<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRadiationInspectionsToEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->integer('periodic_radiation_inspection')->comment("Kiểm xạ định kỳ")->nullable();
            $table->date('last_radiation_inspection')->comment("Kiểm xạ lần cuối")->nullable()->after("periodic_radiation_inspection");
            $table->date('next_radiation_inspection')->comment("Kiểm xạ lần kế tiếp")->nullable()->after("last_radiation_inspection");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipments', function (Blueprint $table) {

            $table->dropColumn('periodic_radiation_inspection');
            $table->dropColumn('last_radiation_inspection');
            $table->dropColumn('next_radiation_inspection');
        });
    }
}
