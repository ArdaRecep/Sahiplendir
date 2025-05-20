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
    Schema::create('listings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')
              ->constrained('site_users')
              ->cascadeOnDelete();
        $table->string('title', 255);
        $table->text('description');
        $table->foreignId('category_id')
              ->constrained()
              ->cascadeOnDelete();
        $table->string('city');
        $table->string('district');
        $table->string('neighborhood');
        $table->string('quarter');
        $table->string('postal_code');
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
