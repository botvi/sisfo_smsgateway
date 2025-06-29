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
        Schema::create('monitoring_absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wali_kelas_id');
            $table->foreignId('siswa_id');
            $table->longText('pesan');
            $table->string('status');
            $table->date('tanggal_pengiriman');

            $table->timestamps();

            $table->foreign('wali_kelas_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_absensis');
    }
};
