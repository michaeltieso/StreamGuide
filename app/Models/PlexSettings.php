<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlexSettings extends Model
{
    protected $fillable = [
        'access_token',
        'username',
        'email',
        'server_name',
        'server_version',
        'machine_identifier',
        'connection_url',
        'server_access_token'
    ];

    protected static $instance = null;

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = static::first();
            if (!static::$instance) {
                static::$instance = static::create([
                    'access_token' => null,
                    'username' => null,
                    'email' => null,
                    'server_name' => null,
                    'server_version' => null,
                    'machine_identifier' => null,
                    'connection_url' => null,
                    'server_access_token' => null
                ]);
            }
        }

        return static::$instance;
    }

    public function clearSettings()
    {
        $this->update([
            'access_token' => null,
            'username' => null,
            'email' => null,
            'server_name' => null,
            'server_version' => null,
            'machine_identifier' => null,
            'connection_url' => null,
            'server_access_token' => null
        ]);
    }
}