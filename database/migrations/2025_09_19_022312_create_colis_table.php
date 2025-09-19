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
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
            $table->string('numero_courrier')->unique(); // Numéro de courrier unique
            $table->string('destination'); // Destination
            $table->string('nom_expediteur'); // Nom de l'expéditeur
            $table->string('telephone_expediteur'); // Téléphone de l'expéditeur
            $table->string('nom_beneficiaire'); // Nom du bénéficiaire
            $table->string('telephone_beneficiaire'); // Téléphone du bénéficiaire
            $table->integer('montant'); // Montant en FCFA
            $table->integer('valeur_colis'); // Valeur du colis en FCFA
            $table->string('type_colis'); // Type de colis
            $table->string('agence_reception'); // Agence de réception
            $table->text('description')->nullable(); // Description
            $table->string('photo_courrier')->nullable(); // Photo du courrier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};
