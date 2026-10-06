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
        Schema::create('zona_rossa_media', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('media_type')->default('image'); // 'image' or 'video'
            $table->string('title')->nullable();
            $table->text('caption')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zona_rossa_media');
    }
};
