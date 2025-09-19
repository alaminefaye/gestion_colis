<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agence extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'libelle',
        'adresse',
        'telephone',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Scope pour les agences actives
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
