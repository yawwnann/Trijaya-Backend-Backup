<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('regencies')) {
            Schema::create('regencies', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('province_id');
                $table->string('name');

                $table->foreign('province_id')->references('id')->on('provinces')->cascadeOnDelete();
                $table->index('province_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('regencies');
    }
};


