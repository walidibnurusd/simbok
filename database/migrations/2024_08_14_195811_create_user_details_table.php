<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('nip')->unique();
            $table->string('employee_name');
            $table->string('phone_wa');            
            $table->bigInteger('gender')->unsigned()->nullable();
            $table->foreign('gender')->references('id')->on('genders');
            $table->bigInteger('religion')->unsigned()->nullable();
            $table->foreign('religion')->references('id')->on('religions');
            $table->bigInteger('marrital_status')->unsigned();
            $table->foreign('marrital_status')->references('id')->on('marrital_statuses');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('current_address');            
            $table->bigInteger('education')->unsigned();
            $table->foreign('education')->references('id')->on('educations');
            $table->bigInteger('profession')->unsigned();
            $table->foreign('profession')->references('id')->on('professions');
            $table->bigInteger('employee_status')->unsigned();
            $table->foreign('employee_status')->references('id')->on('employee_statuses');
            $table->bigInteger('position')->unsigned();
            $table->foreign('position')->references('id')->on('positions');
            $table->bigInteger('rank')->unsigned();
            $table->foreign('rank')->references('id')->on('ranks'); // Assuming this refers to a foreign key or enum
            $table->date('tmt_pangkat');
            $table->bigInteger('group')->unsigned();
            $table->foreign('group')->references('id')->on('groups');
            $table->date('tmt_golongan');
            $table->string('photo')->nullable();  // Path to the uploaded file
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
        Schema::dropIfExists('user_details');
    }
};
