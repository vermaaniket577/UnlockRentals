<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('visitors') && !Schema::hasColumn('visitors', 'ip_address')) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->string('ip_address', 45)->nullable()->after('operating_system');
            });
        }

        if (Schema::hasTable('visitor_sessions')) {
            Schema::table('visitor_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('visitor_sessions', 'ip_address')) {
                    $table->string('ip_address', 45)->nullable()->after('operating_system');
                }
                if (!Schema::hasColumn('visitor_sessions', 'city')) {
                    $table->string('city', 80)->nullable()->after('ip_address');
                }
                if (!Schema::hasColumn('visitor_sessions', 'state')) {
                    $table->string('state', 80)->nullable()->after('city');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('visitors') && Schema::hasColumn('visitors', 'ip_address')) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->dropColumn('ip_address');
            });
        }

        if (Schema::hasTable('visitor_sessions')) {
            Schema::table('visitor_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('visitor_sessions', 'ip_address')) {
                    $table->dropColumn('ip_address');
                }
                if (Schema::hasColumn('visitor_sessions', 'city')) {
                    $table->dropColumn('city');
                }
                if (Schema::hasColumn('visitor_sessions', 'state')) {
                    $table->dropColumn('state');
                }
            });
        }
    }
};
