<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

#[Signature('backup:run')]
#[Description('Dump the MariaDB database and storage, encrypt the .env, and upload everything to the offsite remote')]
class BackupDatabase extends Command
{
    public function handle(): int
    {
        $tmpPath = config('backup.tmp_path');
        $remote = config('backup.rclone_remote');
        $keepDays = (int) config('backup.keep_days');
        $mysqlDefaultsFile = config('backup.mysql_defaults_file');
        $envPassphraseFile = config('backup.env_passphrase_file');

        if (! File::exists($mysqlDefaultsFile)) {
            $this->components->error("Ficheiro de credenciais do MariaDB não encontrado: {$mysqlDefaultsFile}");

            return self::FAILURE;
        }

        if (! File::exists($envPassphraseFile)) {
            $this->components->error("Ficheiro de passphrase do .env não encontrado: {$envPassphraseFile}");

            return self::FAILURE;
        }

        File::ensureDirectoryExists($tmpPath);

        $date = now()->format('Ymd-His');
        $database = config('database.connections.mysql.database');

        $dump = Process::timeout(300)->run(sprintf(
            'mariadb-dump --defaults-extra-file=%s --single-transaction --routines --triggers %s | gzip > %s',
            escapeshellarg($mysqlDefaultsFile),
            escapeshellarg($database),
            escapeshellarg("{$tmpPath}/database-{$date}.sql.gz"),
        ));
        $this->components->task('A fazer dump da base de dados', fn () => $dump->successful());

        if (! $dump->successful()) {
            $this->components->error('Falha ao fazer dump da base de dados.');

            return self::FAILURE;
        }

        $storage = Process::timeout(300)->run(sprintf(
            'tar -czf %s -C %s storage/app',
            escapeshellarg("{$tmpPath}/storage-{$date}.tar.gz"),
            escapeshellarg(base_path()),
        ));
        $this->components->task('A arquivar storage/app', fn () => $storage->successful());

        $env = Process::timeout(60)->run(sprintf(
            'gpg --batch --yes --symmetric --cipher-algo AES256 --passphrase-file %s -o %s %s',
            escapeshellarg($envPassphraseFile),
            escapeshellarg("{$tmpPath}/env-{$date}.gpg"),
            escapeshellarg(base_path('.env')),
        ));
        $this->components->task('A cifrar o .env', fn () => $env->successful());

        $upload = Process::timeout(600)->run(sprintf(
            'rclone copy %s %s --transfers=4',
            escapeshellarg($tmpPath),
            escapeshellarg("{$remote}/".now()->format('Y-m')),
        ));
        $this->components->task('A enviar para o destino remoto', fn () => $upload->successful());

        if (! $upload->successful()) {
            $this->components->error('Falha ao enviar o backup para o destino remoto.');

            return self::FAILURE;
        }

        Process::run(sprintf('find %s -type f -mtime +1 -delete', escapeshellarg($tmpPath)));
        Process::timeout(120)->run(sprintf(
            'rclone delete %s --min-age %dd',
            escapeshellarg($remote),
            $keepDays,
        ));

        $this->components->info('Backup concluído.');

        return self::SUCCESS;
    }
}
