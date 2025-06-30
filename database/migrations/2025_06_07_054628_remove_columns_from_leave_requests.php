<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (Schema::hasColumn('leave_requests', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('leave_requests', 'department')) {
                $table->dropColumn('department');
            }
            if (Schema::hasColumn('leave_requests', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('leave_requests', 'nip')) {
                $table->dropColumn('nip');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            //
        });
    }
};
