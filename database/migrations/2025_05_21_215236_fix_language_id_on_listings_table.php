<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1) Mevcut language_id kolonu varsa sil
        if (Schema::hasColumn('listings', 'language_id')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('language_id');
            });
        }

        // 2) Tekrar ekleyelim: nullable, FK ve nullOnDelete
        Schema::table('listings', function (Blueprint $table) {
            $table->foreignId('language_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            // FK zaten otomatik olarak silinir column drop ile
            $table->dropColumn('language_id');
        });
    }
};
