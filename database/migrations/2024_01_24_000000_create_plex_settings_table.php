<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plex_settings', function (Blueprint $table) {
            $table->id();
            $table->string('access_token')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('server_name')->nullable();
            $table->string('server_version')->nullable();
            $table->string('machine_identifier')->nullable();
            $table->string('server_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plex_settings');
    }
}; 