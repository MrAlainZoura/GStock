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
        Schema::create('produit_transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfert_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('produit_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->integer('quantite');
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_transferts');
    }
};
