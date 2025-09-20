<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bagage extends Model
{
    use HasFactory;

    protected $table = 'bagages';

    protected $fillable = [
        'numero',
        'possede_ticket',
        'numero_ticket',
        'destination',
        'nom_famille',
        'prenom',
        'telephone',
        'valeur',
        'montant',
        'poids',
        'contenu'
    ];

    protected $casts = [
        'possede_ticket' => 'boolean',
        'valeur' => 'decimal:2',
        'montant' => 'decimal:2',
        'poids' => 'decimal:2'
    ];
}
