<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('leave_requests', 'department')) {
                $table->string('department')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('leave_requests', 'phone_number')) {
                $table->string('phone_number', 20)->nullable()->after('department');
            }
            if (!Schema::hasColumn('leave_requests', 'nip')) {
                $table->string('nip', 20)->nullable()->after('phone_number');
            }
        });
    }

    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['department', 'phone_number', 'nip']);
        });
    }
};