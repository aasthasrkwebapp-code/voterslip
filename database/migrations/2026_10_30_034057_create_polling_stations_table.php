
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polling_stations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('election_id')
                ->constrained('elections')
                ->cascadeOnDelete();

            $table->foreignId('ward_id')
                ->constrained('wards')
                ->cascadeOnDelete();

            $table->string('booth_number');
            $table->string('polling_station_name')->nullable();
            $table->string('building_name')->nullable();
            $table->string('room_number')->nullable();
            $table->text('address')->nullable();

            $table->timestamps();

            $table->unique(
                ['election_id', 'ward_id', 'booth_number'],
                'polling_station_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polling_stations');
    }
};
