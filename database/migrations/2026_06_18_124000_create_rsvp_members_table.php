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
        Schema::create('rsvp_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rsvp_id')->constrained('rsvps')->onDelete('cascade');
            $table->string('member_type'); // spouse, companion, child
            $table->string('first_name');
            $table->string('last_name');
            $table->boolean('will_attend');
            $table->boolean('is_pregnant')->default(false);
            $table->integer('age')->nullable(); // only for children
            $table->boolean('needs_highchair')->default(false); // only for children
            $table->boolean('needs_baby_menu')->default(false); // only for children
            $table->text('allergies')->nullable();
            $table->text('dietary_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp_members');
    }
};
