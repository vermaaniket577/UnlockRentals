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
        Schema::table('callback_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('callback_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('callback_requests', 'called_at')) {
                $table->timestamp('called_at')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('callback_requests', 'called_by')) {
                $table->unsignedBigInteger('called_by')->nullable()->after('called_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callback_requests', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('callback_requests', 'admin_notes')) {
                $columns[] = 'admin_notes';
            }
            if (Schema::hasColumn('callback_requests', 'called_at')) {
                $columns[] = 'called_at';
            }
            if (Schema::hasColumn('callback_requests', 'called_by')) {
                $columns[] = 'called_by';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
