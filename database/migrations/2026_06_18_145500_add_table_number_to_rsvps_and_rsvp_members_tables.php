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
            $table->integer('table_number')->nullable();
        });

        Schema::table('rsvp_members', function (Blueprint $table) {
            $table->integer('table_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rsvps', function (Blueprint $table) {
            $table->dropColumn('table_number');
        });

        Schema::table('rsvp_members', function (Blueprint $table) {
            $table->dropColumn('table_number');
        });
    }
};
