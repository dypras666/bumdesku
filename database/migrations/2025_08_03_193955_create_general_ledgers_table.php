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
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('entry_code')->unique(); // Unique entry code
            $table->foreignId('account_id')->constrained('master_accounts')->onDelete('cascade');
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->date('posting_date');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->text('description');
            $table->string('reference_type')->nullable(); // Type of reference document
            $table->string('reference_number')->nullable(); // Reference document number
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('posted_at')->nullable();
            $table->enum('status', ['draft', 'posted', 'reversed'])->default('draft');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['account_id', 'posting_date']);
            $table->index(['transaction_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_ledgers');
    }
};
