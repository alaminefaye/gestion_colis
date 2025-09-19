<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_complet',
        'telephone',
        'email'
    ];

    /**
     * Méthode pour créer ou récupérer un client par téléphone
     */
    public static function createOrUpdate($nom_complet, $telephone, $email = null)
    {
        return self::updateOrCreate(
            ['telephone' => $telephone],
            [
                'nom_complet' => $nom_complet,
                'email' => $email
            ]
        );
    }

    /**
     * Relations avec les colis comme expéditeur
     */
    public function colisEnvoyes()
    {
        return $this->hasMany(Colis::class, 'telephone_expediteur', 'telephone');
    }

    /**
     * Relations avec les colis comme bénéficiaire
     */
    public function colisRecus()
    {
        return $this->hasMany(Colis::class, 'telephone_beneficiaire', 'telephone');
    }
}
