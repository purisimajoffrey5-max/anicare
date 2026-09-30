<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;
use RuntimeException;

class SuperAdminBackupService
{
    public function create(): array
    {
        $timestamp = now()->format('Y-m-d_H-i-s');

        $workDir = storage_path(
            'app/private/super-admin-work/' . Str::uuid()
        );

        $backupDir = storage_path(
            'app/private/super-admin-backups'
        );

        File::ensureDirectoryExists($workDir);
        File::ensureDirectoryExists($backupDir);

        try {

            /*
            |--------------------------------------------------------------------------
            | 1. CREATE DATABASE.SQL
            |--------------------------------------------------------------------------
            */

            $sqlPath = $workDir . '/database.sql';

            $this->createSqlDump($sqlPath);


            /*
            |--------------------------------------------------------------------------
            | 2. COPY UPLOADED FILES
            |--------------------------------------------------------------------------
            */

            $this->copyDirectoryIfExists(
                storage_path('app/public'),
                $workDir . '/storage/app/public'
            );

            $this->copyDirectoryIfExists(
                public_path('uploads'),
                $workDir . '/public/uploads'
            );


            /*
            |--------------------------------------------------------------------------
            | 3. CREATE MANIFEST
            |--------------------------------------------------------------------------
            */

            File::put(
                $workDir . '/manifest.json',
                json_encode([
                    'backup_format' => 'ANI-CARE Super Admin SQL Backup v1',
                    'created_at' => now()->toIso8601String(),
                    'application' => config('app.name'),
                    'laravel_version' => app()->version(),
                    'php_version' => PHP_VERSION,
                    'database' => DB::getDatabaseName(),

                    'included' => [
                        'database.sql',
                        'storage/app/public',
                        'public/uploads',
                    ],

                    'excluded' => [
                        '.env',
                        'application secrets',
                        'password reset tokens',
                    ],

                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );


            /*
            |--------------------------------------------------------------------------
            | 4. CREATE ZIP
            |--------------------------------------------------------------------------
            */

            $zipPath = $backupDir .
                '/anicare-recovery-' .
                $timestamp .
                '.zip';

            $zip = new ZipArchive();

            if (
                $zip->open(
                    $zipPath,
                    ZipArchive::CREATE | ZipArchive::OVERWRITE
                ) !== true
            ) {
                throw new RuntimeException(
                    'Unable to create backup ZIP archive.'
                );
            }

            $this->addDirectoryToZip(
                $zip,
                $workDir,
                ''
            );

            $zip->close();


            /*
            |--------------------------------------------------------------------------
            | 5. RETURN BACKUP INFORMATION
            |--------------------------------------------------------------------------
            */

            return [
                'path' => $zipPath,
                'filename' => basename($zipPath),
                'size' => File::size($zipPath),
            ];

        } finally {

            /*
            |--------------------------------------------------------------------------
            | DELETE TEMPORARY WORKING DIRECTORY
            |--------------------------------------------------------------------------
            */

            File::deleteDirectory($workDir);
        }
    }


    /**
     * Create an actual MySQL .sql dump.
     */
    private function createSqlDump(string $sqlPath): void
    {
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        /*
        |--------------------------------------------------------------------------
        | XAMPP mysqldump
        |--------------------------------------------------------------------------
        */

        $mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

        if (!File::exists($mysqldump)) {
            throw new RuntimeException(
                'mysqldump.exe was not found at: ' . $mysqldump
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Build command
        |--------------------------------------------------------------------------
        */

        $command =
            '"' . $mysqldump . '"' .
            ' --host=' . escapeshellarg($host) .
            ' --port=' . escapeshellarg($port) .
            ' --user=' . escapeshellarg($username) .
            ' --password=' . escapeshellarg($password) .
            ' --single-transaction' .
            ' --routines' .
            ' --triggers' .
            ' --events' .
            ' --default-character-set=utf8mb4' .
            ' ' . escapeshellarg($database) .
            ' > ' . escapeshellarg($sqlPath);


        /*
        |--------------------------------------------------------------------------
        | Execute mysqldump
        |--------------------------------------------------------------------------
        */

        $output = [];
        $exitCode = 0;

        exec(
            $command . ' 2>&1',
            $output,
            $exitCode
        );


        /*
        |--------------------------------------------------------------------------
        | Check result
        |--------------------------------------------------------------------------
        */

        if ($exitCode !== 0) {

            throw new RuntimeException(
                'Database SQL backup failed: ' .
                implode(PHP_EOL, $output)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Make sure SQL file exists
        |--------------------------------------------------------------------------
        */

        if (
            !File::exists($sqlPath) ||
            File::size($sqlPath) === 0
        ) {
            throw new RuntimeException(
                'Database SQL backup was created but is empty.'
            );
        }
    }


    /**
     * Copy a directory if it exists.
     */
    private function copyDirectoryIfExists(
        string $source,
        string $destination
    ): void {

        if (File::isDirectory($source)) {

            File::copyDirectory(
                $source,
                $destination
            );
        }
    }


    /**
     * Add directory contents to ZIP.
     */
    private function addDirectoryToZip(
        ZipArchive $zip,
        string $directory,
        string $prefix
    ): void {

        $files = File::allFiles($directory);

        foreach ($files as $file) {

            $relative = ltrim(
                str_replace(
                    $directory,
                    '',
                    $file->getPathname()
                ),
                DIRECTORY_SEPARATOR
            );

            $zip->addFile(
                $file->getPathname(),
                ($prefix ? $prefix . '/' : '') .
                str_replace(
                    DIRECTORY_SEPARATOR,
                    '/',
                    $relative
                )
            );
        }
    }
}