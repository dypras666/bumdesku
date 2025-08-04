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
        Schema::table('general_ledgers', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['transaction_id']);
            
            // Modify the column to be nullable
            $table->foreignId('transaction_id')->nullable()->change();
            
            // Re-add the foreign key constraint with nullable
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            // Drop the nullable foreign key constraint
            $table->dropForeign(['transaction_id']);
            
            // Modify the column back to not nullable
            $table->foreignId('transaction_id')->change();
            
            // Re-add the foreign key constraint without nullable
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }
};
