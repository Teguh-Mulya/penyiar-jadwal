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
        Schema::create('ganti_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id');
            $table->foreignId('broadcaster_id'); // Assuming broadcasters are users
            $table->foreignId('submitter_id'); // Assuming broadcasters are users
            $table->text('reason');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ganti_jadwals');
    }
};
