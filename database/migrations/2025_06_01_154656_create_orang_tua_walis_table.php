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
        Schema::create('orang_tua_walis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ortu');
            $table->string('no_hp_ortu');
            $table->date('tanggal_lahir_ortu');
            $table->text('alamat_ortu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua_walis');
    }
};
