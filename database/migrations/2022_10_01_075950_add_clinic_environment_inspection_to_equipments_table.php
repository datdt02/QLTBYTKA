<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClinicEnvironmentInspectionToEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->integer('period_of_clinic_environment_inspection')
                ->comment("KIểm định môi trường phòng định kỳ")->nullable();

            $table->date('last_clinic_environment_inspection')
                ->comment("KIểm định môi trường phòng lần cuối")->nullable()
                ->after("period_of_clinic_environment_inspection");

            $table->date('next_clinic_environment_inspection')
                ->comment("KIểm định môi trường phòng lần kế tiếp")->nullable()
                ->after("last_clinic_environment_inspection");

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
            $table->dropColumn('period_of_clinic_environment_inspection');
            $table->dropColumn('last_clinic_environment_inspection');
            $table->dropColumn('next_clinic_environment_inspection');

        });
    }
}
