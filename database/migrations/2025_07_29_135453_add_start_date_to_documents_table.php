<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('documents', function (Blueprint $table) {
        // Menambahkan kolom tanggal_mulai setelah nama_dokumen
        $table->date('tanggal_mulai')->nullable()->after('nama_dokumen');
    });
}

public function down(): void
{
    Schema::table('documents', function (Blueprint $table) {
        $table->dropColumn('tanggal_mulai');
    });
}
};
