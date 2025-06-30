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
    Schema::create('leaves', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke tabel users
        $table->string('type'); // jenis cuti
        $table->date('start'); // tanggal mulai
        $table->date('end'); // tanggal akhir
        $table->enum('status', ['requested', 'approved', 'rejected'])->default('requested'); // status cuti
        $table->timestamps();
    });
}
   /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};


