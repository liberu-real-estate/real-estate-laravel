<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            foreach ([
                'name' => fn($t) => $t->string('name')->nullable(),
                'contact' => fn($t) => $t->string('contact')->nullable(),
                'notes' => fn($t) => $t->text('notes')->nullable(),
                'property_address' => fn($t) => $t->string('property_address')->nullable(),
                'property_type' => fn($t) => $t->string('property_type')->nullable(),
                'area_sqft' => fn($t) => $t->integer('area_sqft')->nullable(),
                'bedrooms' => fn($t) => $t->integer('bedrooms')->nullable(),
                'bathrooms' => fn($t) => $t->integer('bathrooms')->nullable(),
                'calendar_event_id' => fn($t) => $t->string('calendar_event_id')->nullable(),
            ] as $col => $fn) {
                if (!Schema::hasColumn('appointments', $col)) {
                    $fn($table);
                }
            }
            if (!Schema::hasColumn('appointments', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable();
                $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $cols = array_filter(
                ['name', 'contact', 'notes', 'staff_id', 'property_address', 'property_type', 'area_sqft', 'bedrooms', 'bathrooms', 'calendar_event_id'],
                fn($c) => Schema::hasColumn('appointments', $c)
            );
            if (in_array('staff_id', $cols)) {
                try { $table->dropForeign(['staff_id']); } catch (\Exception $e) {}
            }
            if (!empty($cols)) {
                $table->dropColumn(array_values($cols));
            }
        });
    }
};
