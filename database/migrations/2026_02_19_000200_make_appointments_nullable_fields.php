<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Columns are already nullable in the base appointments migration
        // This migration is a no-op to maintain compatibility
    }

    public function down(): void
    {
        // No-op
    }
};
