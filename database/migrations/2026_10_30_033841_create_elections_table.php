
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('election_type')->nullable();
            $table->string('district')->nullable();
            $table->string('panchayat_samiti')->nullable();
            $table->string('gram_panchayat')->nullable();
            $table->string('assembly_constituency')->nullable();
            $table->string('year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
