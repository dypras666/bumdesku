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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique(); // Kode pembayaran otomatis
            $table->foreignId('loan_id')->constrained('loans')->onDelete('cascade'); // Link ke pinjaman
            $table->date('payment_date'); // Tanggal pembayaran
            $table->decimal('payment_amount', 15, 2); // Jumlah pembayaran
            $table->decimal('principal_amount', 15, 2); // Pokok
            $table->decimal('interest_amount', 15, 2)->default(0); // Bunga
            $table->decimal('penalty_amount', 15, 2)->default(0); // Denda keterlambatan
            $table->integer('installment_number'); // Cicilan ke-
            $table->enum('payment_method', ['cash', 'transfer', 'check', 'other'])->default('cash');
            $table->text('notes')->nullable(); // Catatan pembayaran
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions'); // Link ke transaksi jurnal
            $table->foreignId('created_by')->constrained('users'); // User yang input
            $table->timestamp('approved_at')->nullable(); // Tanggal approve
            $table->foreignId('approved_by')->nullable()->constrained('users'); // User yang approve
            $table->timestamps();
            
            // Indexes
            $table->index(['loan_id', 'payment_date']);
            $table->index(['status', 'payment_date']);
            $table->index('payment_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
