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
        Schema::create('collection_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->double('quantite');
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_produits');
    }
};
