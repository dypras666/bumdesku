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
        Schema::table('loans', function (Blueprint $table) {
            // Jenis pinjaman: bunga, bagi_hasil, tanpa_bunga
            $table->enum('loan_type', ['bunga', 'bagi_hasil', 'tanpa_bunga'])->default('bunga')->after('loan_amount');
            
            // Untuk pinjaman bagi hasil
            $table->decimal('profit_sharing_percentage', 5, 2)->nullable()->after('interest_rate')->comment('Persentase bagi hasil (%)');
            $table->decimal('expected_profit', 15, 2)->nullable()->after('profit_sharing_percentage')->comment('Estimasi keuntungan usaha');
            
            // Untuk semua jenis pinjaman
            $table->decimal('admin_fee', 15, 2)->default(0)->after('expected_profit')->comment('Biaya administrasi');
            $table->text('business_description')->nullable()->after('notes')->comment('Deskripsi usaha untuk bagi hasil');
            
            // Index untuk pencarian berdasarkan jenis pinjaman
            $table->index('loan_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['loan_type']);
            $table->dropColumn([
                'loan_type',
                'profit_sharing_percentage',
                'expected_profit',
                'admin_fee',
                'business_description'
            ]);
        });
    }
};
