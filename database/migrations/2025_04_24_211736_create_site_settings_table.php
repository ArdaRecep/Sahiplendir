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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->json('data')->nullable();
            $table->uuid('group_id')->nullable();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string("logo")->nullable();
            $table->string("footer_logo")->nullable();
            $table->string("fav_icon")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
