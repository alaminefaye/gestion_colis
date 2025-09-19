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
        Schema::table('colis', function (Blueprint $table) {
            // Statut du colis pour le suivi livraison
            $table->enum('statut_livraison', ['en_attente', 'ramasse', 'en_transit', 'livre'])->default('en_attente');
            
            // Livreur qui a ramassé le colis
            $table->foreignId('ramasse_par')->nullable()->constrained('livreurs')->onDelete('set null');
            $table->timestamp('ramasse_le')->nullable();
            
            // Livreur qui a livré le colis
            $table->foreignId('livre_par')->nullable()->constrained('livreurs')->onDelete('set null');
            $table->timestamp('livre_le')->nullable();
            
            // QR Code unique pour chaque colis
            $table->string('qr_code')->unique()->nullable();
            
            // Notes de livraison
            $table->text('notes_ramassage')->nullable();
            $table->text('notes_livraison')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colis', function (Blueprint $table) {
            $table->dropColumn([
                'statut_livraison',
                'ramasse_par',
                'ramasse_le',
                'livre_par', 
                'livre_le',
                'qr_code',
                'notes_ramassage',
                'notes_livraison'
            ]);
        });
    }
};
