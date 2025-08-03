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
        Schema::create('master_units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->unique();
            $table->string('nama_unit');
            $table->string('kategori_unit');
            $table->decimal('nilai_aset', 15, 2)->default(0);
            $table->text('alamat')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_units');
    }
};
