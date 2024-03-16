<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ip_geolocations', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip');
            $table->string('provider', 50)->default('default');
            $table->unique(['ip', 'provider']);
            $table->string('hostname')->nullable();
            $table->string('country')->nullable();
            $table->string('countryCode')->nullable();
            $table->string('region')->nullable();
            $table->string('regionName')->nullable();
            $table->string('city')->nullable();
            $table->string('zip')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->string('timezone')->nullable();
            $table->string('isp')->nullable();
            $table->string('org')->nullable();
            $table->string('as')->nullable();
            $table->boolean('mobile')->nullable();
            $table->boolean('proxy')->nullable();
            $table->boolean('hosting')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_geolocations');
    }
};
