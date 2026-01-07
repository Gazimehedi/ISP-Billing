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
        // Using raw SQL to avoid doctrine/dbal dependency
        DB::statement("ALTER TABLE employees MODIFY salary DECIMAL(10, 2) NULL DEFAULT 0");
        DB::statement("ALTER TABLE employees MODIFY due DECIMAL(10, 2) NULL DEFAULT 0");
        DB::statement("ALTER TABLE employees MODIFY salary_deduction DECIMAL(10, 2) NULL DEFAULT 0");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ensure no nulls before reverting
        DB::statement("UPDATE employees SET salary = 0 WHERE salary IS NULL");
        DB::statement("UPDATE employees SET due = 0 WHERE due IS NULL");
        DB::statement("UPDATE employees SET salary_deduction = 0 WHERE salary_deduction IS NULL");

        DB::statement("ALTER TABLE employees MODIFY salary DECIMAL(10, 2) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE employees MODIFY due DECIMAL(10, 2) NOT NULL DEFAULT 0");
        DB::statement("ALTER TABLE employees MODIFY salary_deduction DECIMAL(10, 2) NOT NULL DEFAULT 0");
    }
};
