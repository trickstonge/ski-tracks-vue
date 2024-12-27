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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('rating')->nullable();
            $table->dateTime('start');
            $table->dateTime('finish');
            $table-> string('description');
            $table->enum('activity', ['skiing', 'ski-touring', 'x-country'])->default('skiing');
            $table->float('duration');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
