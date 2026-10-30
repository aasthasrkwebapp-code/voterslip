
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->string('ward_number');
            $table->string('ward_name')->nullable();
            $table->string('village_name')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['election_id', 'ward_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
