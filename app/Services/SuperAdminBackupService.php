<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class SuperAdminBackupService
{
    /**
     * Tables that should not be included in a recovery backup.
     */
    private array $excludedTables = [
        'password_reset_tokens',
        'personal_access_tokens',
    ];

    /**
     * Create a complete ANI-CARE recovery backup.
     *
     * This version does NOT use:
     * - exec()
     * - mysqldump
     * - mysql.exe
     *
     * Therefore it works on hosting environments where shell
     * execution is disabled.
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
            | 1. CREATE DATABASE SNAPSHOT
            |--------------------------------------------------------------------------
            */

            $databasePath = $workDir . '/database.json';

            $this->createDatabaseSnapshot($databasePath);

            /*
            |--------------------------------------------------------------------------
            | 2. COPY PUBLIC STORAGE FILES
            |--------------------------------------------------------------------------
            */

            $this->copyDirectoryIfExists(
                storage_path('app/public'),
                $workDir . '/storage/app/public'
            );

            /*
            |--------------------------------------------------------------------------
            | 3. COPY PUBLIC UPLOADS
            |--------------------------------------------------------------------------
            */

            $this->copyDirectoryIfExists(
                public_path('uploads'),
                $workDir . '/public/uploads'
            );

            /*
            |--------------------------------------------------------------------------
            | 4. CREATE MANIFEST
            |--------------------------------------------------------------------------
            */

            File::put(
                $workDir . '/manifest.json',
                json_encode(
                    [
                        'backup_format' =>
                            'ANI-CARE Super Admin PHP Backup v4',

                        'created_at' =>
                            now()->toIso8601String(),

                        'application' =>
                            config('app.name'),

                        'laravel_version' =>
                            app()->version(),

                        'php_version' =>
                            PHP_VERSION,

                        'database_driver' =>
                            DB::getDriverName(),

                        'database' =>
                            DB::getDatabaseName(),

                        'included' => [
                            'database.json',
                            'storage/app/public',
                            'public/uploads',
                        ],

                        'excluded' => [
                            '.env',
                            'application secrets',
                            'password_reset_tokens',
                            'personal_access_tokens',
                        ],

                        'notes' => [
                            'Database backup is generated using Laravel/PHP.',
                            'No shell commands are required.',
                            'No mysqldump executable is required.',
                            'No mysql executable is required.',
                            'Database structure and table data are included.',
                        ],
                    ],
                    JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
                )
            );

            /*
            |--------------------------------------------------------------------------
            | 5. CREATE ZIP
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
                    ZipArchive::CREATE |
                    ZipArchive::OVERWRITE
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
            | 6. VERIFY ZIP
            |--------------------------------------------------------------------------
            */

            if (
                !File::exists($zipPath) ||
                File::size($zipPath) <= 0
            ) {
                throw new RuntimeException(
                    'Backup ZIP was created but is empty.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 7. RETURN BACKUP INFORMATION
            |--------------------------------------------------------------------------
            */

            return [
                'path' => $zipPath,

                'filename' =>
                    basename($zipPath),

                'size' =>
                    File::size($zipPath),
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
     * Restore an ANI-CARE PHP backup ZIP.
     *
     * No exec(), mysql.exe, or mysqldump is required.
     */
    public function restore(string $zipPath): array
    {
        if (!File::exists($zipPath)) {
            throw new RuntimeException(
                'The backup file does not exist.'
            );
        }

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
            | 3. VERIFY REQUIRED FILES
            |--------------------------------------------------------------------------
            */

            $manifestPath =
                $workDir . '/manifest.json';

            $databasePath =
                $workDir . '/database.json';

            if (
                !File::exists($manifestPath) ||
                !File::exists($databasePath)
            ) {
                throw new RuntimeException(
                    'Invalid ANI-CARE backup. ' .
                    'manifest.json or database.json is missing.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 4. READ MANIFEST
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
                    'ANI-CARE Super Admin PHP Backup'
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

            $restoreResult =
                $this->restoreDatabaseSnapshot(
                    $databasePath
                );

            /*
            |--------------------------------------------------------------------------
            | 6. RESTORE STORAGE FILES
            |--------------------------------------------------------------------------
            */

            $this->restoreDirectoryIfExists(
                $workDir . '/storage/app/public',
                storage_path('app/public')
            );

            /*
            |--------------------------------------------------------------------------
            | 7. RESTORE UPLOADS
            |--------------------------------------------------------------------------
            */

            $this->restoreDirectoryIfExists(
                $workDir . '/public/uploads',
                public_path('uploads')
            );

            /*
            |--------------------------------------------------------------------------
            | 8. RETURN RESULT
            |--------------------------------------------------------------------------
            */

            return [
                'restored_rows' =>
                    $restoreResult['restored_rows'],

                'skipped_tables' =>
                    $restoreResult['skipped_tables'],

                'restored_tables' =>
                    $restoreResult['restored_tables'],

                'database_restored' =>
                    true,

                'files_restored' =>
                    true,
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
     * Create a PHP/Laravel database snapshot.
     *
     * The snapshot contains:
     *
     * - table name
     * - CREATE TABLE statement
     * - table rows
     */
    private function createDatabaseSnapshot(
        string $databasePath
    ): void {
        $driver = DB::getDriverName();

        if ($driver !== 'mysql') {
            throw new RuntimeException(
                'The Super Admin PHP backup currently supports MySQL/MariaDB only.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Get all base tables
        |--------------------------------------------------------------------------
        */

        $tables = DB::select(
            'SHOW FULL TABLES WHERE Table_type = ?',
            ['BASE TABLE']
        );

        $snapshot = [
            'format' =>
                'ANI-CARE Database Snapshot v1',

            'created_at' =>
                now()->toIso8601String(),

            'database' =>
                DB::getDatabaseName(),

            'driver' =>
                $driver,

            'tables' => [],
        ];

        foreach ($tables as $tableRow) {
            $tableName = $this->extractTableName(
                $tableRow
            );

            if ($tableName === null) {
                continue;
            }

            if (
                in_array(
                    $tableName,
                    $this->excludedTables,
                    true
                )
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get CREATE TABLE
            |--------------------------------------------------------------------------
            */

            $createResult = DB::select(
                'SHOW CREATE TABLE ' .
                $this->quoteIdentifier($tableName)
            );

            if (empty($createResult)) {
                continue;
            }

            $createRow = (array) $createResult[0];

            $createSql = null;

            foreach ($createRow as $key => $value) {
                if (
                    str_contains(
                        strtolower((string) $key),
                        'create table'
                    )
                ) {
                    $createSql = $value;
                    break;
                }
            }

            if (
                !is_string($createSql) ||
                trim($createSql) === ''
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Get table data
            |--------------------------------------------------------------------------
            */

            $rows = DB::table($tableName)
                ->get()
                ->map(
                    fn ($row) => (array) $row
                )
                ->values()
                ->all();

            $snapshot['tables'][] = [
                'name' =>
                    $tableName,

                'create_sql' =>
                    $createSql,

                'row_count' =>
                    count($rows),

                'rows' =>
                    $rows,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Write snapshot
        |--------------------------------------------------------------------------
        */

        $json = json_encode(
            $snapshot,
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );

        if ($json === false) {
            throw new RuntimeException(
                'Unable to encode database backup: ' .
                json_last_error_msg()
            );
        }

        if (!File::put($databasePath, $json)) {
            throw new RuntimeException(
                'Unable to write database backup file.'
            );
        }

        if (
            !File::exists($databasePath) ||
            File::size($databasePath) <= 0
        ) {
            throw new RuntimeException(
                'Database backup file was created but is empty.'
            );
        }
    }


    /**
     * Restore the PHP/Laravel database snapshot.
     */
    private function restoreDatabaseSnapshot(
        string $databasePath
    ): array {
        if (!File::exists($databasePath)) {
            throw new RuntimeException(
                'Database snapshot file does not exist.'
            );
        }

        $contents = File::get(
            $databasePath
        );

        $snapshot = json_decode(
            $contents,
            true
        );

        if (!is_array($snapshot)) {
            throw new RuntimeException(
                'Database snapshot is invalid JSON.'
            );
        }

        if (
            ($snapshot['format'] ?? null) !==
            'ANI-CARE Database Snapshot v1'
        ) {
            throw new RuntimeException(
                'Unsupported database snapshot format.'
            );
        }

        if (
            !isset($snapshot['tables']) ||
            !is_array($snapshot['tables'])
        ) {
            throw new RuntimeException(
                'Database snapshot contains no table definitions.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Current database
        |--------------------------------------------------------------------------
        */

        $driver = DB::getDriverName();

        if ($driver !== 'mysql') {
            throw new RuntimeException(
                'Database restore currently supports MySQL/MariaDB only.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Count tables
        |--------------------------------------------------------------------------
        */

        $restoredTables = 0;
        $skippedTables = 0;
        $restoredRows = 0;

        /*
        |--------------------------------------------------------------------------
        | Disable foreign key checks
        |--------------------------------------------------------------------------
        */

        DB::statement(
            'SET FOREIGN_KEY_CHECKS=0'
        );

        try {
            /*
            |--------------------------------------------------------------------------
            | IMPORTANT:
            | Drop tables first.
            |--------------------------------------------------------------------------
            */

            foreach ($snapshot['tables'] as $table) {
                $tableName =
                    $table['name'] ?? null;

                if (
                    !is_string($tableName) ||
                    $tableName === ''
                ) {
                    $skippedTables++;
                    continue;
                }

                if (
                    in_array(
                        $tableName,
                        $this->excludedTables,
                        true
                    )
                ) {
                    $skippedTables++;
                    continue;
                }

                DB::statement(
                    'DROP TABLE IF EXISTS ' .
                    $this->quoteIdentifier(
                        $tableName
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Recreate tables
            |--------------------------------------------------------------------------
            */

            foreach ($snapshot['tables'] as $table) {
                $tableName =
                    $table['name'] ?? null;

                $createSql =
                    $table['create_sql'] ?? null;

                if (
                    !is_string($tableName) ||
                    $tableName === '' ||
                    !is_string($createSql) ||
                    trim($createSql) === ''
                ) {
                    $skippedTables++;
                    continue;
                }

                if (
                    in_array(
                        $tableName,
                        $this->excludedTables,
                        true
                    )
                ) {
                    $skippedTables++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Create table
                |--------------------------------------------------------------------------
                */

                DB::statement(
                    $createSql
                );

                $restoredTables++;

                /*
                |--------------------------------------------------------------------------
                | Restore rows
                |--------------------------------------------------------------------------
                */

                $rows =
                    $table['rows'] ?? [];

                if (
                    !is_array($rows) ||
                    empty($rows)
                ) {
                    continue;
                }

                foreach (
                    array_chunk($rows, 500)
                    as $chunk
                ) {
                    $insertRows = [];

                    foreach ($chunk as $row) {
                        if (is_array($row)) {
                            $insertRows[] = $row;
                        }
                    }

                    if (empty($insertRows)) {
                        continue;
                    }

                    DB::table($tableName)
                        ->insert($insertRows);

                    $restoredRows +=
                        count($insertRows);
                }
            }
        } catch (\Throwable $e) {
            throw new RuntimeException(
                'Database restore failed: ' .
                $e->getMessage(),
                0,
                $e
            );
        } finally {
            /*
            |--------------------------------------------------------------------------
            | ALWAYS RE-ENABLE FOREIGN KEYS
            |--------------------------------------------------------------------------
            */

            DB::statement(
                'SET FOREIGN_KEY_CHECKS=1'
            );
        }

        return [
            'restored_rows' =>
                $restoredRows,

            'restored_tables' =>
                $restoredTables,

            'skipped_tables' =>
                $skippedTables,
        ];
    }


    /**
     * Extract table name from SHOW FULL TABLES result.
     */
    private function extractTableName(
        object $tableRow
    ): ?string {
        $data = (array) $tableRow;

        foreach ($data as $key => $value) {
            if (
                str_ends_with(
                    strtolower((string) $key),
                    'tables_in_' .
                    strtolower(DB::getDatabaseName())
                )
            ) {
                return is_string($value)
                    ? $value
                    : null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback:
        | The first column is normally the table name.
        |--------------------------------------------------------------------------
        */

        $values = array_values($data);

        if (
            isset($values[0]) &&
            is_string($values[0])
        ) {
            return $values[0];
        }

        return null;
    }


    /**
     * Safely quote a MySQL identifier.
     */
    private function quoteIdentifier(
        string $identifier
    ): string {
        if (
            !preg_match(
                '/^[A-Za-z0-9_$]+$/',
                $identifier
            )
        ) {
            throw new RuntimeException(
                'Invalid database identifier.'
            );
        }

        return '`' .
            str_replace(
                '`',
                '``',
                $identifier
            ) .
            '`';
    }


    /**
     * Copy a directory if it exists.
     */
    private function copyDirectoryIfExists(
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