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
        Schema::table('leave_requests', function (Blueprint $table) {
            // Hanya tambahkan kolom status jika belum ada
            if (!Schema::hasColumn('leave_requests', 'status')) {
                $table->string('status')->default('Pending')->after('document');
            }
        });
    }

        public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};