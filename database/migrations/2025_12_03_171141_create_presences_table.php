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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('depot_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->float('distance')->nullable();
            $table->boolean('confirm')->default(false);
            $table->string('confirm_raison')->nullable();
            $table->string('ip')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->string('city')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
