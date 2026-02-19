<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('name')->nullable()->after('status');
            $table->string('contact')->nullable()->after('name');
            $table->text('notes')->nullable()->after('contact');
            $table->unsignedBigInteger('staff_id')->nullable()->after('notes');
            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
            $table->string('property_address')->nullable()->after('staff_id');
            $table->string('property_type')->nullable()->after('property_address');
            $table->integer('area_sqft')->nullable()->after('property_type');
            $table->integer('bedrooms')->nullable()->after('area_sqft');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
            $table->string('calendar_event_id')->nullable()->after('bathrooms');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn([
                'name', 'contact', 'notes', 'staff_id',
                'property_address', 'property_type', 'area_sqft',
                'bedrooms', 'bathrooms', 'calendar_event_id',
            ]);
        });
    }
};
