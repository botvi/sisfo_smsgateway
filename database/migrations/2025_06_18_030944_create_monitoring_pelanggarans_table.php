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
        Schema::create('monitoring_pelanggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_bk_id');
            $table->foreignId('pelanggaran_id');
            $table->foreignId('siswa_id');

            $table->foreign('guru_bk_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pelanggaran_id')->references('id')->on('pelanggarans')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_pelanggarans');
    }
};
