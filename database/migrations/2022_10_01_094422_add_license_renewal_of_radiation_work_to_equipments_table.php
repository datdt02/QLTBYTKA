<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLicenseRenewalOfRadiationWorkToEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->integer('period_of_license_renewal_of_radiation_work')
                ->comment("Gia hạn giấy phép tiến hành CV bức xạ định kỳ")->nullable();

            $table->date('last_license_renewal_of_radiation_work')
                ->comment("Gia hạn giấy phép tiến hành CV bức xạ lần cuối")->nullable()
                ->after("period_of_license_renewal_of_radiation_work");

            $table->date('next_license_renewal_of_radiation_work')
                ->comment("Gia hạn giấy phép tiến hành CV bức xạ lần kế tiếp")->nullable()
                ->after("last_license_renewal_of_radiation_work");

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
            $table->dropColumn('period_of_license_renewal_of_radiation_work');
            $table->dropColumn('last_license_renewal_of_radiation_work');
            $table->dropColumn('next_license_renewal_of_radiation_work');
//
        });
    }
}
