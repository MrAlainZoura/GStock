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
        Schema::create('reservation_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('reservation_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->string('duree')->nullable();
            $table->timestamp('debut')->nullable();
            $table->timestamp('fin')->nullable();
            $table->double('montant')->nullable();
            $table->double('reduction')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_produits');
    }
};
