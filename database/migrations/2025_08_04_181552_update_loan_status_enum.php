<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum to include pending and approved status
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('pending', 'approved', 'active', 'completed', 'overdue', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE loans MODIFY COLUMN status ENUM('active', 'completed', 'overdue', 'cancelled') DEFAULT 'active'");
    }
};
