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
            $table->string('nip')->unique();
            $table->string('nama_pegawai');
            $table->string('telpon_wa');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('agama');
            $table->string('status_menikah');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('alamat_sekarang');
            $table->string('status_akhir')->nullable();
            $table->integer('pendidikan');  // Assuming this refers to a foreign key or enum
            $table->integer('profesi');  // Assuming this refers to a foreign key or enum
            $table->string('status_pegawai');
            $table->integer('jabatan');  // Assuming this refers to a foreign key or enum
            $table->integer('pangkat');  // Assuming this refers to a foreign key or enum
            $table->date('tmt_pangkat');
            $table->integer('golongan');  // Assuming this refers to a foreign key or enum
            $table->date('tmt_golongan');
            $table->string('foto')->nullable();  // Path to the uploaded file
            $table->string('password');
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
