<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('districts')) {
            Schema::create('districts', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->string('regency_id');
                $table->string('name');

                $table->foreign('regency_id')->references('id')->on('regencies')->cascadeOnDelete();
                $table->index('regency_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};


