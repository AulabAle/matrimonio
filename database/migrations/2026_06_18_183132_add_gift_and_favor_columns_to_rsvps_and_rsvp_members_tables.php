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
        Schema::table('rsvps', function (Blueprint $table) {
            $table->boolean('has_gift')->default(false);
            $table->boolean('receives_favor')->default(false);
        });

        Schema::table('rsvp_members', function (Blueprint $table) {
            $table->boolean('has_gift')->default(false);
            $table->boolean('receives_favor')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn(['has_gift', 'receives_favor']);
        });

        Schema::table('rsvp_members', function (Blueprint $table) {
            $table->dropColumn(['has_gift', 'receives_favor']);
        });
    }
};
