<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->string('locality')->nullable()->after('house_number');
            $table->string('status', 5)->nullable()->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('voters', function (Blueprint $table) {
            $table->dropColumn(['locality', 'status']);
        });
    }
};
