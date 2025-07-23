<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ikan', function (Blueprint $table) {
            // Tambahkan kolom 'stok' setelah kolom 'harga'
            // unsignedInteger artinya tidak bisa negatif
            // default(0) artinya jika tidak diisi, nilainya 0
            $table->unsignedInteger('stok')->default(0)->after('harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ikan', function (Blueprint $table) {
            // Hapus kolom 'stok' jika migrasi di-rollback
            $table->dropColumn('stok');
        });
    }
};