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
        Schema::table('users', function (Blueprint $table) {
            $table->string('genre')->nullable();
            $table->string('naissance')->nullable();
            $table->string('fonction')->nullable();
            $table->string('niveauEtude')->nullable();
            $table->string('option')->nullable();
            $table->string('adresse')->nullable();
            $table->string('tel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
