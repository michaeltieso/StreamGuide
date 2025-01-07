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
        Schema::table('plex_settings', function (Blueprint $table) {
            // Add new server_access_token field
            $table->string('server_access_token')->nullable();
            
            // Rename server_url to connection_url
            $table->renameColumn('server_url', 'connection_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plex_settings', function (Blueprint $table) {
            // Drop the new field
            $table->dropColumn('server_access_token');
            
            // Rename connection_url back to server_url
            $table->renameColumn('connection_url', 'server_url');
        });
    }
};
