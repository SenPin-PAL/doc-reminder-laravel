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
            // Menambahkan kolom user_id setelah kolom id
            // foreignId -> tipe data unsignedBigInteger
            // constrained -> membuat foreign key ke tabel users
            // cascadeOnDelete -> jika user dihapus, dokumennya juga ikut terhapus
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Logika untuk menghapus kolom jika migrasi di-rollback
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};