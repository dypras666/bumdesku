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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique(); // Kode pinjaman otomatis
            $table->string('borrower_name'); // Nama peminjam
            $table->string('borrower_phone'); // No HP peminjam
            $table->string('borrower_address')->nullable(); // Alamat peminjam
            $table->string('borrower_id_number')->nullable(); // No KTP/Identitas
            $table->decimal('loan_amount', 15, 2); // Jumlah pinjaman
            $table->decimal('interest_rate', 5, 2)->default(0); // Bunga per bulan (%)
            $table->integer('loan_term_months'); // Jangka waktu (bulan)
            $table->decimal('monthly_payment', 15, 2); // Cicilan per bulan
            $table->date('loan_date'); // Tanggal pinjaman
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->enum('status', ['active', 'completed', 'overdue', 'cancelled'])->default('active');
            $table->decimal('total_paid', 15, 2)->default(0); // Total sudah dibayar
            $table->decimal('remaining_balance', 15, 2); // Sisa saldo
            $table->text('notes')->nullable(); // Catatan
            $table->foreignId('account_id')->constrained('master_accounts'); // Link ke akun piutang
            $table->foreignId('created_by')->constrained('users'); // User yang input
            $table->timestamp('approved_at')->nullable(); // Tanggal approve
            $table->foreignId('approved_by')->nullable()->constrained('users'); // User yang approve
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'due_date']);
            $table->index('borrower_name');
            $table->index('loan_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
