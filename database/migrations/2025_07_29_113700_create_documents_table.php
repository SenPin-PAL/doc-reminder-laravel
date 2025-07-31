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
        Schema::create('documents', function (Blueprint $table) {
            // Membuat kolom 'id' sebagai primary key auto-increment
            $table->id();

            // Membuat kolom 'nama_dokumen' untuk menyimpan nama dokumen (tipe VARCHAR)
            $table->string('nama_dokumen');

            // Membuat kolom 'tanggal_deadline' untuk menyimpan tanggal (tipe DATE)
            $table->date('tanggal_deadline');

            // Membuat kolom 'created_at' dan 'updated_at' secara otomatis
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
        Schema::dropIfExists('documents');
    }
};