<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'calendar_event_id')) {
                $table->string('calendar_event_id')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'booking_type')) {
                $table->string('booking_type')->default('viewing');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $cols = array_filter(['calendar_event_id', 'booking_type'], 
                fn($c) => Schema::hasColumn('bookings', $c));
            if ($cols) $table->dropColumn(array_values($cols));
        });
    }
};
