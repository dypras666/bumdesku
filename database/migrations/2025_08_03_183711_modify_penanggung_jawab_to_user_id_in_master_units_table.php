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
        Schema::table('master_units', function (Blueprint $table) {
            // Hapus kolom penanggung_jawab yang lama
            $table->dropColumn('penanggung_jawab');
            
            // Tambah kolom penanggung_jawab_id sebagai foreign key ke users
            $table->foreignId('penanggung_jawab_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_units', function (Blueprint $table) {
            // Hapus foreign key constraint dan kolom penanggung_jawab_id
            $table->dropForeign(['penanggung_jawab_id']);
            $table->dropColumn('penanggung_jawab_id');
            
            // Kembalikan kolom penanggung_jawab yang lama
            $table->string('penanggung_jawab')->nullable();
        });
    }
};
