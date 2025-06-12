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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->string('listing_no', 20)
                ->nullable()
                ->unique();
            $table->foreignId('user_id')
                ->constrained('site_users')
                ->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description');
            $table->json('photos')->nullable();
            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('sub_category_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('city_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('district_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('neigborhood_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('quarter_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('postal_code');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
