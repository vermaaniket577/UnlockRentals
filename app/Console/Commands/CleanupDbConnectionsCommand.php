<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDbConnectionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:cleanup-connections {--max-idle=15 : Maximum idle sleep time in seconds before killing} {--all-sleep : Kill all sleeping connections immediately}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kill sleeping and stalled MySQL connections to prevent and fix error 1040 (Too many connections)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'])) {
            $this->info("Current database driver is '{$driver}'. MySQL connection cleanup is not applicable.");
            return 0;
        }

        $maxIdle = (int) $this->option('max-idle');
        $allSleep = (bool) $this->option('all-sleep');

        $this->info("Inspecting MySQL processlist (Driver: {$driver})...");

        try {
            $currentId = DB::selectOne("SELECT CONNECTION_ID() as conn_id")?->conn_id;
            $currentDb = DB::getDatabaseName();

            $status = DB::select("SHOW STATUS LIKE 'Threads_connected'");
            $threadsConnected = $status[0]->Value ?? 'unknown';

            $maxConnRes = DB::select("SHOW VARIABLES LIKE 'max_connections'");
            $maxConnections = $maxConnRes[0]->Value ?? 'unknown';

            $this->line("Threads Connected: <fg=yellow>{$threadsConnected}</> / Max Connections: <fg=cyan>{$maxConnections}</>");

            $processes = DB::select("SHOW FULL PROCESSLIST");
            $killed = 0;

            foreach ($processes as $proc) {
                $pId = $proc->Id ?? null;
                $pCommand = $proc->Command ?? '';
                $pTime = (int) ($proc->Time ?? 0);
                $pDb = $proc->db ?? '';

                // Don't kill our own connection
                if ($pId == $currentId) {
                    continue;
                }

                // Identify connections to kill:
                // 1. Sleeping connections older than maxIdle (or all sleep if flag set)
                // 2. Hanging queries taking longer than 60 seconds
                $shouldKill = false;
                if ($pCommand === 'Sleep' && ($allSleep || $pTime >= $maxIdle)) {
                    $shouldKill = true;
                } elseif ($pCommand === 'Query' && $pTime > 60) {
                    $shouldKill = true;
                }

                if ($shouldKill) {
                    try {
                        DB::statement("KILL {$pId}");
                        $killed++;
                        $this->line("Killed PID {$pId} [Command: {$pCommand}, Time: {$pTime}s, DB: {$pDb}]");
                    } catch (\Throwable $e) {
                        // Might already have closed
                    }
                }
            }

            $this->info("Successfully terminated {$killed} idle/stuck connections.");

            // Reset session wait timeouts
            try {
                DB::statement("SET SESSION wait_timeout=15, SESSION interactive_timeout=15");
            } catch (\Throwable $e) {}

            return 0;
        } catch (\Throwable $e) {
            $this->error("Connection cleanup failed: " . $e->getMessage());
            return 1;
        } finally {
            try {
                DB::disconnect();
            } catch (\Throwable $e) {}
        }
    }
}
