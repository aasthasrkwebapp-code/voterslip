
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('ward_id')
                ->constrained('wards')
                ->cascadeOnDelete();

            $table->foreignId('polling_station_id')
                ->constrained('polling_stations')
                ->cascadeOnDelete();

            $table->string('serial_number')->nullable();
            $table->string('epic_number', 30)->nullable()->index();

            $table->string('name');
            $table->string('relative_name')->nullable();
            $table->string('relation_type')->nullable();

            $table->string('house_number')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender', 20)->nullable();

            $table->string('part_number')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            $table->index(['name', 'age']);
            $table->index(['election_id', 'ward_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
