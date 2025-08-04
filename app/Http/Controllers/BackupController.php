<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display backup management page
     */
    public function index()
    {
        $backups = $this->getBackupFiles();
        return view('backups.index', compact('backups'));
    }

    /**
     * Create a new database backup
     */
    public function create(Request $request)
    {
        try {
            $filename = 'backup_' . config('database.connections.mysql.database') . '_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sql';
            
            // Run backup command
            $exitCode = Artisan::call('backup:database', [
                '--filename' => $filename
            ]);

            if ($exitCode === 0) {
                return redirect()->route('backups.index')
                    ->with('success', 'Backup database berhasil dibuat: ' . $filename);
            } else {
                return redirect()->route('backups.index')
                    ->with('error', 'Gagal membuat backup database');
            }
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file
     */
    public function download($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!file_exists($backupPath)) {
            return redirect()->route('backups.index')
                ->with('error', 'File backup tidak ditemukan');
        }

        return Response::download($backupPath);
    }

    /**
     * Delete a backup file
     */
    public function destroy($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (file_exists($backupPath)) {
                unlink($backupPath);
                return redirect()->route('backups.index')
                    ->with('success', 'File backup berhasil dihapus');
            } else {
                return redirect()->route('backups.index')
                    ->with('error', 'File backup tidak ditemukan');
            }
        } catch (\Exception $e) {
            return redirect()->route('backups.index')
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get list of backup files
     */
    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (is_dir($backupPath)) {
            $files = scandir($backupPath);
            
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupPath . '/' . $file;
                    $backups[] = [
                        'filename' => $file,
                        'size' => $this->formatBytes(filesize($filePath)),
                        'created_at' => Carbon::createFromTimestamp(filemtime($filePath)),
                        'path' => $filePath
                    ];
                }
            }
            
            // Sort by creation time (newest first)
            usort($backups, function($a, $b) {
                return $b['created_at']->timestamp - $a['created_at']->timestamp;
            });
        }

        return $backups;
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
