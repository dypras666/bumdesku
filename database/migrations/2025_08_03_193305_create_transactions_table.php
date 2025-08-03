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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); // Kode transaksi unik
            $table->enum('transaction_type', ['income', 'expense']); // Jenis transaksi: pemasukan/pengeluaran
            $table->date('transaction_date'); // Tanggal transaksi
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->text('description'); // Keterangan transaksi
            $table->foreignId('account_id')->constrained('master_accounts')->onDelete('cascade'); // FK ke master_accounts
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // FK ke users (yang input)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Status transaksi
            $table->timestamp('approved_at')->nullable(); // Waktu persetujuan
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Yang menyetujui
            $table->text('notes')->nullable(); // Catatan tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
