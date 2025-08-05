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
            // Drop the unique constraint on entry_code
            // In accounting, the same entry_code should be used for both debit and credit sides
            $table->dropUnique(['entry_code']);
            
            // Add an index for performance (non-unique)
            $table->index('entry_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_ledgers', function (Blueprint $table) {
            // Remove the index
            $table->dropIndex(['entry_code']);
            
            // Restore the unique constraint
            $table->unique('entry_code');
        });
    }
};
