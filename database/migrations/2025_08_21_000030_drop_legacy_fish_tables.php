<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop FKs referencing legacy tables before dropping them
        if (Schema::hasTable('keranjang_items')) {
            Schema::table('keranjang_items', function (Blueprint $table) {
                // Safely drop foreign key if it exists
                try {
                    $table->dropForeign(['ikan_id']);
                } catch (\Throwable $e) {
                }
            });
        }

        // Now drop legacy tables if they exist
        Schema::dropIfExists('ikan_pesanan');
        Schema::dropIfExists('ikan');
        Schema::dropIfExists('kategori_ikan');
    }

    public function down(): void
    {
        // Recreate minimal placeholders to allow rollback (structure may differ from original)
        if (!Schema::hasTable('kategori_ikan')) {
            Schema::create('kategori_ikan', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ikan')) {
            Schema::create('ikan', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->nullable();
                $table->foreignId('kategori_id')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('ikan_pesanan')) {
            Schema::create('ikan_pesanan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ikan_id')->nullable();
                $table->foreignId('pesanan_id')->nullable();
                $table->integer('qty')->nullable();
                $table->timestamps();
            });
        }

        // Optionally restore FK on keranjang_items
        if (Schema::hasTable('keranjang_items')) {
            Schema::table('keranjang_items', function (Blueprint $table) {
                try {
                    $table->foreign('ikan_id')->references('id')->on('ikan')->onDelete('cascade');
                } catch (\Throwable $e) {
                }
            });
        }
    }
};


