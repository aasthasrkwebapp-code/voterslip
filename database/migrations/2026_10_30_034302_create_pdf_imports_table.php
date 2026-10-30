
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_imports', function (Blueprint $table) {
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

            $table->string('original_filename');
            $table->string('file_path');
            $table->string('file_hash', 64)->nullable()->index();

            $table->unsignedInteger('total_pages')->default(0);
            $table->unsignedInteger('total_records')->default(0);
            $table->unsignedInteger('imported_records')->default(0);
            $table->unsignedInteger('failed_records')->default(0);

            $table->string('status', 30)->default('pending')->index();
            $table->text('error_message')->nullable();

            $table->timestamp('imported_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_imports');
    }
};
