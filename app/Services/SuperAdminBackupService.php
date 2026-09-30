<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ZipArchive;
use RuntimeException;

class SuperAdminBackupService
{
    /**
     * Create a complete ANI-CARE recovery backup.
     */
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
            | 2. COPY PUBLIC STORAGE FILES
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
                    'backup_format' => 'ANI-CARE Super Admin SQL Backup v2',

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
                        'password_reset_tokens',
                        'personal_access_tokens',
                    ],

                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );


            /*
            |--------------------------------------------------------------------------
            | 4. CREATE ZIP
            |--------------------------------------------------------------------------
            */

            $zipPath =
                $backupDir .
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
            | DELETE TEMPORARY WORK DIRECTORY
            |--------------------------------------------------------------------------
            */

            File::deleteDirectory($workDir);
        }
    }


    /**
     * Restore an ANI-CARE SQL backup ZIP.
     */
    public function restore(string $zipPath): array
    {
        $workDir = storage_path(
            'app/private/super-admin-work/' . Str::uuid()
        );

        File::ensureDirectoryExists($workDir);

        $zip = new ZipArchive();

        /*
        |--------------------------------------------------------------------------
        | 1. OPEN ZIP
        |--------------------------------------------------------------------------
        */

        if ($zip->open($zipPath) !== true) {

            File::deleteDirectory($workDir);

            throw new RuntimeException(
                'The uploaded backup is not a valid ZIP archive.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2. EXTRACT ZIP
        |--------------------------------------------------------------------------
        */

        if (!$zip->extractTo($workDir)) {

            $zip->close();

            File::deleteDirectory($workDir);

            throw new RuntimeException(
                'Unable to extract the backup archive.'
            );
        }

        $zip->close();


        try {

            /*
            |--------------------------------------------------------------------------
            | 3. VERIFY BACKUP FILES
            |--------------------------------------------------------------------------
            */

            $manifestPath =
                $workDir . '/manifest.json';

            $databasePath =
                $workDir . '/database.sql';


            if (
                !File::exists($manifestPath) ||
                !File::exists($databasePath)
            ) {
                throw new RuntimeException(
                    'Invalid ANI-CARE backup. manifest.json or database.sql is missing.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | 4. VERIFY MANIFEST
            |--------------------------------------------------------------------------
            */

            $manifest = json_decode(
                File::get($manifestPath),
                true
            );

            if (!is_array($manifest)) {
                throw new RuntimeException(
                    'The backup manifest is invalid.'
                );
            }


            $backupFormat =
                $manifest['backup_format'] ?? null;


            if (
                !is_string($backupFormat) ||
                !str_starts_with(
                    $backupFormat,
                    'ANI-CARE Super Admin SQL Backup'
                )
            ) {
                throw new RuntimeException(
                    'Unsupported ANI-CARE backup format.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | 5. RESTORE DATABASE
            |--------------------------------------------------------------------------
            */

            $this->restoreSqlDump(
                $databasePath
            );


            /*
            |--------------------------------------------------------------------------
            | 6. RESTORE UPLOADED FILES
            |--------------------------------------------------------------------------
            */

            $this->restoreDirectoryIfExists(
                $workDir . '/storage/app/public',
                storage_path('app/public')
            );


            $this->restoreDirectoryIfExists(
                $workDir . '/public/uploads',
                public_path('uploads')
            );


            /*
            |--------------------------------------------------------------------------
            | 7. RETURN RESULT
            |--------------------------------------------------------------------------
            */

            return [
                'restored_rows' => null,

                'skipped_tables' => 0,

                'database_restored' => true,

                'files_restored' => true,
            ];

        } finally {

            /*
            |--------------------------------------------------------------------------
            | DELETE TEMPORARY RESTORE DIRECTORY
            |--------------------------------------------------------------------------
            */

            File::deleteDirectory($workDir);
        }
    }


    /**
     * Create an actual MySQL SQL dump.
     */
    private function createSqlDump(string $sqlPath): void
    {
        $host =
            config('database.connections.mysql.host');

        $port =
            config('database.connections.mysql.port');

        $database =
            config('database.connections.mysql.database');

        $username =
            config('database.connections.mysql.username');

        $password =
            config('database.connections.mysql.password');


        /*
        |--------------------------------------------------------------------------
        | XAMPP mysqldump
        |--------------------------------------------------------------------------
        */

        $mysqldump =
            'C:\\xampp\\mysql\\bin\\mysqldump.exe';


        if (!File::exists($mysqldump)) {

            throw new RuntimeException(
                'mysqldump.exe was not found at: ' .
                $mysqldump
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Tables that must NOT be included in backup
        |--------------------------------------------------------------------------
        |
        | These may contain temporary authentication/security data.
        |
        */

        $ignoredTables = [
            'password_reset_tokens',
            'personal_access_tokens',
        ];


        /*
        |--------------------------------------------------------------------------
        | Build ignored-table options
        |--------------------------------------------------------------------------
        */

        $ignoreOptions = '';

        foreach ($ignoredTables as $table) {

            $ignoreOptions .=
                ' --ignore-table=' .
                escapeshellarg(
                    $database . '.' . $table
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Build mysqldump command
        |--------------------------------------------------------------------------
        */

        $command =
            '"' . $mysqldump . '"' .

            ' --host=' .
            escapeshellarg($host) .

            ' --port=' .
            escapeshellarg($port) .

            ' --user=' .
            escapeshellarg($username) .

            ' --password=' .
            escapeshellarg($password) .

            ' --single-transaction' .

            ' --routines' .

            ' --triggers' .

            ' --events' .

            ' --default-character-set=utf8mb4' .

            $ignoreOptions .

            ' ' .
            escapeshellarg($database) .

            ' > ' .
            escapeshellarg($sqlPath);


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
        | Verify SQL file
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
     * Restore SQL dump into the configured MySQL database.
     */
    private function restoreSqlDump(string $sqlPath): void
    {
        $host =
            config('database.connections.mysql.host');

        $port =
            config('database.connections.mysql.port');

        $database =
            config('database.connections.mysql.database');

        $username =
            config('database.connections.mysql.username');

        $password =
            config('database.connections.mysql.password');


        /*
        |--------------------------------------------------------------------------
        | XAMPP mysql.exe
        |--------------------------------------------------------------------------
        */

        $mysql =
            'C:\\xampp\\mysql\\bin\\mysql.exe';


        if (!File::exists($mysql)) {

            throw new RuntimeException(
                'mysql.exe was not found at: ' .
                $mysql
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Verify SQL file
        |--------------------------------------------------------------------------
        */

        if (
            !File::exists($sqlPath) ||
            File::size($sqlPath) === 0
        ) {
            throw new RuntimeException(
                'The database.sql file is missing or empty.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Build restore command
        |--------------------------------------------------------------------------
        */

        $command =
            '"' . $mysql . '"' .

            ' --host=' .
            escapeshellarg($host) .

            ' --port=' .
            escapeshellarg($port) .

            ' --user=' .
            escapeshellarg($username) .

            ' --password=' .
            escapeshellarg($password) .

            ' ' .
            escapeshellarg($database) .

            ' < ' .
            escapeshellarg($sqlPath);


        /*
        |--------------------------------------------------------------------------
        | Execute MySQL restore
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
        | Check restore result
        |--------------------------------------------------------------------------
        */

        if ($exitCode !== 0) {

            throw new RuntimeException(
                'Database SQL restore failed: ' .
                implode(PHP_EOL, $output)
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
     * Restore a directory if it exists in the backup.
     */
    private function restoreDirectoryIfExists(
        string $source,
        string $destination
    ): void {

        if (!File::isDirectory($source)) {
            return;
        }

        File::ensureDirectoryExists(
            $destination
        );

        File::copyDirectory(
            $source,
            $destination
        );
    }


    /**
     * Add directory contents to ZIP.
     */
    private function addDirectoryToZip(
        ZipArchive $zip,
        string $directory,
        string $prefix
    ): void {

        $files = File::allFiles(
            $directory
        );

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