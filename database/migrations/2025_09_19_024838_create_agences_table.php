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
        Schema::create('agences', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique(); // Nom de l'agence
            $table->string('libelle'); // Libellé affiché
            $table->string('adresse')->nullable(); // Adresse de l'agence
            $table->string('telephone')->nullable(); // Téléphone de l'agence
            $table->boolean('actif')->default(true); // Statut actif/inactif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agences');
    }
};
