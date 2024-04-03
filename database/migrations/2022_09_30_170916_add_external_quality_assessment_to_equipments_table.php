<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExternalQualityAssessmentToEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->integer('period_of_external_quality_assessment')->comment("Ngoại kiểm định kỳ")
                ->nullable();

            $table->date('last_external_quality_assessment')->comment("Ngoại kiểm lần cuối")
                ->nullable()->after("period_of_external_quality_assessment");

            $table->date('next_external_quality_assessment')->comment("Ngoại kiểm lần kế tiếp")
                ->nullable()->after("last_external_quality_assessment");
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
            $table->dropColumn('period_of_external_quality_assessment');
            $table->dropColumn('last_external_quality_assessment');
            $table->dropColumn('next_external_quality_assessment');
        });
    }
}
