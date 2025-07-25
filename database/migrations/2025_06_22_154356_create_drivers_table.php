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
       Schema::create('drivers', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->string('password')->nullable();
    $table->string('first_name')->nullable();
    $table->string('last_name')->nullable();
    $table->string('phone_number')->nullable();
    $table->boolean('notifications_enabled')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
