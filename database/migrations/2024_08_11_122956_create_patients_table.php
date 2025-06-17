<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->date('dob'); // Date of Birth
            $table->string('place_birth')->nullable();
            $table->bigInteger('gender')->unsigned()->nullable();
            $table->foreign('gender')->references('id')->on('genders');
            $table->string('phone')->nullable();
            $table->bigInteger('marrital_status')->unsigned();
            $table->foreign('marrital_status')->references('id')->on('marrital_statuses');
            $table->string('no_rm')->unique(); // Medical Record Number
            $table->string('blood_type', 3)->nullable();
            $table->bigInteger('occupation')->unsigned();
            $table->foreign('occupation')->references('id')->on('occupations');
            $table->bigInteger('education')->unsigned();
            $table->foreign('education')->references('id')->on('educations');
            $table->string('address');
            $table->string('rw');
            $table->bigInteger('indonesia_province_id')->unsigned()->nullable();
            $table->foreign('indonesia_province_id')->references('id')->on('indonesia_provinces');
            $table->bigInteger('indonesia_city_id')->unsigned()->nullable();
            $table->foreign('indonesia_city_id')->references('id')->on('indonesia_cities');
            $table->bigInteger('indonesia_district_id')->unsigned()->nullable();
            $table->foreign('indonesia_district_id')->references('id')->on('indonesia_districts');
            $table->bigInteger('indonesia_village_id')->unsigned()->nullable();
            $table->foreign('indonesia_village_id')->references('id')->on('indonesia_villages');
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
        Schema::dropIfExists('patients');
    }
};
