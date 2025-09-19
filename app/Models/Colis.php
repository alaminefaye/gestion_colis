<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Colis extends Model
{
    use HasFactory;

    protected $table = 'colis';

    protected $fillable = [
        'numero_courrier',
        'destination',
        'nom_expediteur',
        'telephone_expediteur',
        'nom_beneficiaire',
        'telephone_beneficiaire',
        'montant',
        'valeur_colis',
        'type_colis',
        'agence_reception',
        'description',
        'photo_courrier'
    ];

    protected $casts = [
        'montant' => 'integer',
        'valeur_colis' => 'integer',
    ];
}
