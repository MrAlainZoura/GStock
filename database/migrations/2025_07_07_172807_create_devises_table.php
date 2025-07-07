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
        Schema::create('devises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depot_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            $table->string('libele');
            $table->float('taux');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devises');
    }
};
