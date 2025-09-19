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
            $table->string('photo_piece_recto')->nullable()->after('notes_livraison');
            $table->string('photo_piece_verso')->nullable()->after('photo_piece_recto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colis', function (Blueprint $table) {
            $table->dropColumn(['photo_piece_recto', 'photo_piece_verso']);
        });
    }
};
