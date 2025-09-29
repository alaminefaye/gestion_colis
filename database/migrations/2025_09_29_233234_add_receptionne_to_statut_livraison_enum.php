<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'ENUM pour ajouter 'receptionne'
        DB::statement("ALTER TABLE colis MODIFY COLUMN statut_livraison ENUM('en_attente', 'ramasse', 'en_transit', 'livre', 'receptionne') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'receptionne' de l'ENUM
        DB::statement("ALTER TABLE colis MODIFY COLUMN statut_livraison ENUM('en_attente', 'ramasse', 'en_transit', 'livre') DEFAULT 'en_attente'");
    }
};
