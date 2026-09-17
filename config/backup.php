<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup destination
    |--------------------------------------------------------------------------
    |
    | Rclone remote (as configured with `rclone config`) where nightly backups
    | are uploaded, e.g. "gdrive:blog-isep-backups".
    |
    */

    'rclone_remote' => env('BACKUP_RCLONE_REMOTE', 'gdrive:blog-isep-backups'),

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    */

    'keep_days' => env('BACKUP_KEEP_DAYS', 14),

    /*
    |--------------------------------------------------------------------------
    | Working directory
    |--------------------------------------------------------------------------
    |
    | Local, temporary staging area for the archives before they are uploaded
    | and pruned. Kept outside the repository.
    |
    */

    'tmp_path' => env('BACKUP_TMP_PATH', '/opt/backups/tmp'),

    /*
    |--------------------------------------------------------------------------
    | MariaDB credentials file
    |--------------------------------------------------------------------------
    |
    | Path to a `--defaults-extra-file` ([client] user/password) so the
    | database password never appears on the command line or in this repo.
    |
    */

    'mysql_defaults_file' => env('BACKUP_MYSQL_DEFAULTS_FILE', '/opt/backups/.my.cnf'),

    /*
    |--------------------------------------------------------------------------
    | .env encryption passphrase file
    |--------------------------------------------------------------------------
    */

    'env_passphrase_file' => env('BACKUP_ENV_PASSPHRASE_FILE', '/opt/backups/.env-passphrase'),

];
