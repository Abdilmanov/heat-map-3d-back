<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cadaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kato', function (Blueprint $table) {

            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('city', 255)->comment('city');
            $table->string('info')->comment('Инфо');
            $table->timestamps();
        });

        Schema::create('almaty_building', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->integer('kato_id')->unsigned()->comment('kato_id')->nullable();
            $table->foreign('kato_id')->references('id')->on('kato');
            $table->string('name', 255)->comment('name')->nullable();
            $table->string('street_no', 255)->comment('street_no')->nullable();
            $table->string('longtitude', 255)->comment('longtitude')->nullable();
            $table->string('street', 255)->comment('street')->nullable();
            $table->string('latitude', 255)->comment('latitude')->nullable();
            $table->string('cadastral_number', 255)->comment('cadastral_number')->nullable();
            $table->string('tech_passport', 255)->comment('tech_passport')->nullable();

            $table->timestamps();
        });
        Schema::create('type', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('name', 255)->comment('name');
            $table->timestamps();
        });

        Schema::create('almaty_meter', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->string('name', 255)->comment('name')->nullable();
            $table->string('serial', 255)->comment('serial')->nullable();
            $table->integer('building_id')->unsigned()->comment('from building')->nullable();
            $table->foreign('building_id')->references('id')->on('almaty_building');
            $table->integer('type_id')->unsigned()->comment('from type')->nullable();
            $table->foreign('type_id')->references('id')->on('type');
            $table->timestamps();
        });
        Schema::create('almaty_meter_logs', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->primary('id');
            $table->date("check_date")->comment("check_date")->nullable();
            $table->string('value', 255)->comment('value')->nullable();
            $table->integer('meter_id')->unsigned()->comment('from meter')->nullable();
            $table->foreign('meter_id')->references('id')->on('almaty_meter');
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
        //
    }
}
