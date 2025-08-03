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
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->unique(); // Unique report code
            $table->enum('report_type', ['income_statement', 'balance_sheet', 'cash_flow', 'trial_balance', 'general_ledger']);
            $table->string('report_title');
            $table->date('period_start');
            $table->date('period_end');
            $table->json('report_data')->nullable(); // Store calculated report data
            $table->json('report_parameters')->nullable(); // Store report generation parameters
            $table->enum('status', ['draft', 'generated', 'finalized'])->default('draft');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('generated_at')->nullable();
            $table->foreignId('finalized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('finalized_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['report_type', 'period_start', 'period_end']);
            $table->index(['status']);
            $table->index(['generated_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
