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
        Schema::create('activity_checks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('activity_id')->unsigned()->nullable();
            $table->foreign('activity_id')->references('id')->on('activity_details')->onDelete('cascade');
            $table->boolean('photo');
            $table->boolean('letter_assign');
            $table->boolean('document');
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
        Schema::dropIfExists('activity_checks');
    }
};
