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
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('depot_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->string('code')->unique();
            $table->boolean('confirm')->nullable();
            $table->string('receptionUser')->nullable();
            $table->string('destination');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transferts');
    }
};
