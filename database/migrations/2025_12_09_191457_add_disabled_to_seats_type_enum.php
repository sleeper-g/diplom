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
        // Изменяем enum для поддержки disabled
        \DB::statement("ALTER TABLE seats MODIFY COLUMN type ENUM('VIP', 'regular', 'disabled') DEFAULT 'regular'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно к старому enum
        \DB::statement("ALTER TABLE seats MODIFY COLUMN type ENUM('VIP', 'regular') DEFAULT 'regular'");
    }
};
