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
            $table->unsignedBigInteger('receptionne_par')->nullable()->after('livre_le');
            $table->timestamp('receptionne_le')->nullable()->after('receptionne_par');
            $table->text('notes_reception')->nullable()->after('receptionne_le');
            
            $table->foreign('receptionne_par')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colis', function (Blueprint $table) {
            $table->dropForeign(['receptionne_par']);
            $table->dropColumn(['receptionne_par', 'receptionne_le', 'notes_reception']);
        });
    }
};
