<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Livreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom', 
        'telephone',
        'email',
        'cin',
        'adresse',
        'actif',
        'date_embauche'
    ];

    protected $casts = [
        'actif' => 'boolean',
        'date_embauche' => 'date'
    ];

    /**
     * Relation avec les colis ramassés
     */
    public function colisRamasses()
    {
        return $this->hasMany(Colis::class, 'ramasse_par');
    }

    /**
     * Relation avec les colis livrés
     */
    public function colisLivres()
    {
        return $this->hasMany(Colis::class, 'livre_par');
    }

    /**
     * Tous les colis associés à ce livreur
     */
    public function colis()
    {
        return $this->hasMany(Colis::class, 'ramasse_par')
                    ->orWhere('livre_par', $this->id);
    }

    /**
     * Nom complet du livreur
     */
    public function getNomCompletAttribute()
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * Statut actif/inactif
     */
    public function getStatutAttribute()
    {
        return $this->actif ? 'Actif' : 'Inactif';
    }
}
