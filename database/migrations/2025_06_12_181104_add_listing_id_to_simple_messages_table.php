<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('simple_messages', function (Blueprint $table) {
        $table->unsignedBigInteger('listing_id')->nullable()->after('recipient_id');
    });
}

public function down()
{
    Schema::table('simple_messages', function (Blueprint $table) {
        $table->dropColumn('listing_id');
    });
}
};
