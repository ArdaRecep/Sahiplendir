<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // language_id foreign key
            $table->foreignId('language_id')
                  ->after('id')
                  ->constrained()
                  ->cascadeOnDelete();

            // group_id as UUID
            $table->uuid('group_id')
                  ->after('language_id')
                  ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['language_id']);
            $table->dropColumn(['language_id', 'group_id']);
        });
    }
};
