<?php

namespace App\Livewire\Admin;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Debug extends AdminComponent
{
    public function getSystemInfo(): array
    {
        $dbVersion = 'Unknown';
        try {
            $dbVersion = DB::select('select version() as version')[0]->version;
        } catch (\Exception $e) {
            $dbVersion = 'Error getting version';
        }

        return [
            'PHP Version' => PHP_VERSION,
            'Laravel Version' => app()->version(),
            'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'Server OS' => PHP_OS,
            'Database' => [
                'Driver' => DB::connection()->getDriverName(),
                'Version' => $dbVersion,
            ],
            'Memory Limit' => ini_get('memory_limit'),
            'Max Upload Size' => ini_get('upload_max_filesize'),
            'Max Post Size' => ini_get('post_max_size'),
            'Display Errors' => ini_get('display_errors'),
            'Environment' => app()->environment(),
        ];
    }

    public function getPhpInfo(): string
    {
        ob_start();
        phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
        $info = ob_get_clean();
        
        // Extract the body content only
        preg_match('/<body[^>]*>(.*?)<\/body>/si', $info, $matches);
        return $matches[1] ?? '';
    }

    public function getLatestLogs(): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return ['No log file found'];
        }

        // Get last 50 lines of the log file
        $logs = [];
        $file = new \SplFileObject($logPath, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        
        $lines = new \LimitIterator($file, max(0, $lastLine - 50), $lastLine);
        foreach ($lines as $line) {
            if (trim($line)) {
                $logs[] = $line;
            }
        }

        return array_reverse($logs);
    }

    public function render(): View
    {
        return view('livewire.admin.debug', [
            'systemInfo' => $this->getSystemInfo(),
            'phpInfo' => $this->getPhpInfo(),
            'latestLogs' => $this->getLatestLogs(),
        ]);
    }
} 