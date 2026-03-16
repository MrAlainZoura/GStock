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
        Schema::table('produit_depots', function (Blueprint $table) {
            $table->string('cdf_prix')->nullable();
            // $table->string('devise_prix')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produit_depots', function (Blueprint $table) {
            //
        });
    }
};
