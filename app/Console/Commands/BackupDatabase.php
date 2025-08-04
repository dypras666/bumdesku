<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--filename=} {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            // Get database configuration
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');
            $port = config('database.connections.mysql.port');

            // Generate filename
            $filename = $this->option('filename') ?: 'backup_' . $database . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            
            // Set backup path
            $backupPath = $this->option('path') ?: storage_path('app/backups');
            
            // Create backup directory if it doesn't exist
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $fullPath = $backupPath . '/' . $filename;

            // Build mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers %s > %s',
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($port),
                escapeshellarg($database),
                escapeshellarg($fullPath)
            );

            // Execute backup command
            $returnVar = null;
            $output = null;
            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                $fileSize = $this->formatBytes(filesize($fullPath));
                $this->info("Database backup completed successfully!");
                $this->info("File: {$fullPath}");
                $this->info("Size: {$fileSize}");
                
                return Command::SUCCESS;
            } else {
                $this->error("Database backup failed!");
                $this->error("Command output: " . implode("\n", $output));
                
                return Command::FAILURE;
            }

        } catch (\Exception $e) {
            $this->error("Database backup failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
