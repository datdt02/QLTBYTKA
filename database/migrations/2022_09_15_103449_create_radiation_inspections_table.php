<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRadiationInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radiation_inspections', function (Blueprint $table) {
            $table->id();
            $table->foreignId("equipment_id")->constrained("equipments")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("provider");
            $table->date("time");
            $table->text("note");
            $table->text("content");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radiation_inspections');
    }
}
