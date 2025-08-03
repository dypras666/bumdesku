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
        Schema::create('unit_change_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_unit_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // nama field yang berubah
            $table->text('old_value')->nullable(); // nilai lama
            $table->text('new_value')->nullable(); // nilai baru
            $table->string('action'); // create, update, delete
            $table->string('changed_by')->nullable(); // user yang melakukan perubahan
            $table->text('description')->nullable(); // deskripsi perubahan
            $table->timestamps();
            
            $table->index(['master_unit_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_change_histories');
    }
};
