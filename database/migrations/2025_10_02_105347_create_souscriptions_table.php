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
        Schema::create('souscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            $table->foreignId('abonnement_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();
            $table->integer('duree');
            $table->integer('montant');
            $table->double('remise');
            $table->integer('progres')->default(0);
            $table->integer('bonus' )->nullable();
            $table->string('code')->nullable();
            $table->boolean('used')->default(false);
            $table->boolean('validate')->default(false);
            $table->boolean('fulltime')->default(false);
            $table->dateTime('debut' );
            $table->dateTime('expired' );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('souscriptions');
    }
};
