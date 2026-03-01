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
        Schema::create('paiement_souscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('souscription_id')
                ->constrained()
                ->noActionOnDelete()
                ->cascadeOnUpdate();
            $table->integer('tranche');
            $table->double('avance');
            $table->double('solde');
            $table->double('net');
            $table->boolean('completed');
            $table->string('moyen')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiement_souscriptions');
    }
};
