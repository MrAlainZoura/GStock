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
        Schema::table('depots', function (Blueprint $table) {
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('contact1')->nullable();
            $table->string('contact')->nullable();
            $table->string('cpostal')->nullable();
            $table->string('pays')->nullable();
            $table->string('province')->nullable();
            $table->string('ville')->nullable();
            $table->string('avenue')->nullable();
            $table->string('idNational')->nullable();
            $table->string('numImpot')->nullable();
            $table->longText('autres')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('depots', function (Blueprint $table) {
            //
        });
    }
};
