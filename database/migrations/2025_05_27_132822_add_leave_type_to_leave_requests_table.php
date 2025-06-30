<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom leave_type ke tabel leave_requests.
     */
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Tambahkan kembali leave_type sebelum 'reason'
            if (!Schema::hasColumn('leave_requests', 'leave_type')) {
                $table->string('leave_type')->after('full_name');
            }
        });
    }

    /**
     * Menghapus kolom leave_type jika rollback.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'leave_type')) {
                $table->dropColumn('leave_type');
            }
        });
    }
};