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
        Schema::create('bagages', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // Numéro de bagage unique saisi manuellement
            $table->boolean('possede_ticket')->default(false); // Le client possède un ticket ?
            $table->string('numero_ticket')->nullable(); // N° Ticket (optionnel)
            $table->string('destination'); // Destination
            $table->string('nom_famille'); // Nom de Famille
            $table->string('prenom'); // Prénom(s)
            $table->string('telephone'); // Téléphone
            $table->decimal('valeur', 10, 2)->nullable(); // Valeur
            $table->decimal('montant', 10, 2)->nullable(); // Montant
            $table->decimal('poids', 8, 2)->nullable(); // Poids (kg)
            $table->text('contenu')->nullable(); // Contenu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bagages');
    }
};
