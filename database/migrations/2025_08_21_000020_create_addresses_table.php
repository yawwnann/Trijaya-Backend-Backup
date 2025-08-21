<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->string('label', 50);
                $table->string('recipient_name', 100);
                $table->string('phone', 20);
                $table->text('address');
                $table->string('province');
                $table->string('city');
                $table->string('district');
                $table->string('postal_code');
                $table->boolean('is_default')->default(false);
                $table->text('notes')->nullable();
                $table->string('regency_id')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'is_default']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};


