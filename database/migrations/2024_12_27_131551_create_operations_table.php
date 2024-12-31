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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained()
            ->noActionOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('depot_id')
            ->constrained()
            ->noActionOnDelete()
            ->cascadeOnUpdate();
            $table->foreignId('produit_id')
            ->constrained()
            ->noActionOnDelete()
            ->cascadeOnUpdate();

            $table->string('libele');
            $table->integer('quantite');
            $table->integer('netPayer')->nullable();
            $table->string('client')->nullable();
            $table->string('tel')->nullable();

            $table->integer('destinationDepot')->nullable();
            $table->integer('receptionUser')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
