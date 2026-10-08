<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Convert message column to LONGTEXT on MySQL/MariaDB
        try {
            DB::statement('ALTER TABLE leads MODIFY message LONGTEXT NULL');
        } catch (\Throwable $e) {}

        // 2. Clean up any existing bloated repetitive [API Sync] messages in the table
        try {
            DB::table('leads')
                ->where('message', 'like', '%API Sync%')
                ->orWhere('message', 'like', '%Admission Dekho Update%')
                ->chunkById(50, function ($rows) {
                    foreach ($rows as $row) {
                        if (!empty($row->message) && (strlen($row->message) > 800 || substr_count($row->message, 'API Sync') > 1)) {
                            $lines = preg_split('/\r\n|\r|\n/', (string)$row->message);
                            $cleanLines = [];
                            $seen = [];
                            foreach ($lines as $line) {
                                $trimmed = trim($line);
                                $normalized = preg_replace('/\[(?:API Sync|Admission Dekho Update)[^\]]*\]:?\s*/i', '', $trimmed);
                                if (!empty($normalized)) {
                                    if (!isset($seen[$normalized])) {
                                        $seen[$normalized] = true;
                                        $cleanLines[] = $trimmed;
                                    }
                                }
                            }
                            $cleanMsg = implode("\n", $cleanLines);
                            if (mb_strlen($cleanMsg) > 3000) {
                                $cleanMsg = mb_substr($cleanMsg, 0, 3000);
                            }
                            DB::table('leads')
                                ->where('id', $row->id)
                                ->update(['message' => $cleanMsg]);
                        }
                    }
                });
        } catch (\Throwable $e) {}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement('ALTER TABLE leads MODIFY message TEXT NULL');
        } catch (\Throwable $e) {}
    }
};
