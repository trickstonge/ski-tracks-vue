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
        Schema::create('track_metrics', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Track::class)->constrained()->cascadeOnDelete();
            $table->float('max_speed');
            $table->float('max_descent_speed');
            $table->float('max_ascent_speed');
            $table->float('max_descent_steepness');
            $table->float('max_ascent_steepness');
            $table->float('total_ascent');
            $table->float('total_descent');
            $table->float('max_altitude');
            $table->float('min_altitude');
            $table->float('distance');
            $table->float('descent_distance');
            $table->float('ascent_distance');
            $table->float('average_speed');
            $table->float('average_descent_speed');
            $table->float('average_ascent_speed');
            $table->float('start_altitude');
            $table->float('finish_altitude');
            $table->integer('ascents')->nullable();
            $table->integer('descents')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_metrics');
    }
};
