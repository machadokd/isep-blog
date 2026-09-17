<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Tests\TestCase;

class BackupDatabaseCommandTest extends TestCase
{
    public function test_it_fails_when_mysql_defaults_file_is_missing(): void
    {
        Process::fake();

        config(['backup.mysql_defaults_file' => '/nonexistent/.my.cnf']);

        $this->artisan('backup:run')
            ->assertFailed();

        Process::assertNothingRan();
    }

    public function test_it_fails_when_env_passphrase_file_is_missing(): void
    {
        Process::fake();

        config([
            'backup.mysql_defaults_file' => $this->fakeCredentialsFile(),
            'backup.env_passphrase_file' => '/nonexistent/.env-passphrase',
        ]);

        $this->artisan('backup:run')
            ->assertFailed();

        Process::assertNothingRan();
    }

    public function test_it_dumps_the_database_and_uploads_the_backup(): void
    {
        Process::fake();

        config([
            'backup.mysql_defaults_file' => $this->fakeCredentialsFile(),
            'backup.env_passphrase_file' => $this->fakeCredentialsFile(),
            'backup.tmp_path' => sys_get_temp_dir().'/backup-test-'.uniqid(),
            'database.connections.mysql.database' => 'blog_isep',
        ]);

        $this->artisan('backup:run')
            ->assertSuccessful();

        Process::assertRan(fn ($process) => str_contains($process->command, 'mariadb-dump')
            && str_contains($process->command, 'blog_isep'));

        Process::assertRan(fn ($process) => str_contains($process->command, 'rclone copy'));
    }

    public function test_it_fails_when_the_database_dump_fails(): void
    {
        Process::fake([
            'mariadb-dump*' => Process::result(errorOutput: 'access denied', exitCode: 1),
        ]);

        config([
            'backup.mysql_defaults_file' => $this->fakeCredentialsFile(),
            'backup.env_passphrase_file' => $this->fakeCredentialsFile(),
            'backup.tmp_path' => sys_get_temp_dir().'/backup-test-'.uniqid(),
        ]);

        $this->artisan('backup:run')
            ->assertFailed();

        Process::assertNotRan(fn ($process) => str_contains($process->command, 'rclone copy'));
    }

    private function fakeCredentialsFile(): string
    {
        $path = sys_get_temp_dir().'/backup-test-credentials-'.uniqid();

        File::put($path, '[client]');

        $this->beforeApplicationDestroyed(fn () => File::delete($path));

        return $path;
    }
}
