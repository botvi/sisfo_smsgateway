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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->date('tanggal_lahir_siswa');
            $table->string('alamat_siswa');
            $table->string('nisn_siswa')->nullable();
            $table->foreignId('master_kelas_id');
            $table->foreignId('orang_tua_wali_id');
            
            
            $table->timestamps();

            $table->foreign('master_kelas_id')->references('id')->on('master_kelas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('orang_tua_wali_id')->references('id')->on('orang_tua_walis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
