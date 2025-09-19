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
            // Récupération à la gare
            $table->boolean('recupere_gare')->default(false);
            $table->timestamp('recupere_le')->nullable();
            $table->string('recupere_par_nom')->nullable();
            $table->string('recupere_par_telephone')->nullable();
            $table->string('recupere_par_cin')->nullable();
            $table->text('notes_recuperation')->nullable();
            $table->foreignId('recupere_par_user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colis', function (Blueprint $table) {
            $table->dropColumn([
                'recupere_gare',
                'recupere_le',
                'recupere_par_nom',
                'recupere_par_telephone',
                'recupere_par_cin',
                'notes_recuperation',
                'recupere_par_user_id'
            ]);
        });
    }
};
