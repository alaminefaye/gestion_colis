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
            $table->boolean('est_receptionne')->default(false)->after('description');
            $table->string('receptionne_par')->nullable()->after('est_receptionne');
            $table->timestamp('receptionne_le')->nullable()->after('receptionne_par');
            $table->text('notes_reception')->nullable()->after('receptionne_le');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('colis', function (Blueprint $table) {
            $table->dropColumn(['est_receptionne', 'receptionne_par', 'receptionne_le', 'notes_reception']);
        });
    }
};
