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
        'photo_courrier',
        'statut_livraison',
        'ramasse_par',
        'ramasse_le',
        'livre_par',
        'livre_le',
        'qr_code',
        'notes_ramassage',
        'notes_livraison',
        'recupere_gare',
        'recupere_le',
        'recupere_par_nom',
        'recupere_par_telephone',
        'recupere_par_cin',
        'notes_recuperation',
        'recupere_par_user_id'
    ];

    protected $casts = [
        'montant' => 'integer',
        'valeur_colis' => 'integer',
        'ramasse_le' => 'datetime',
        'livre_le' => 'datetime',
        'recupere_gare' => 'boolean',
        'recupere_le' => 'datetime',
    ];

    /**
     * Relations avec les livreurs
     */
    public function livreurRamassage()
    {
        return $this->belongsTo(Livreur::class, 'ramasse_par');
    }

    public function livreurLivraison()
    {
        return $this->belongsTo(Livreur::class, 'livre_par');
    }

    /**
     * Utilisateur qui a enregistré la récupération
     */
    public function recupereParUser()
    {
        return $this->belongsTo(User::class, 'recupere_par_user_id');
    }

    /**
     * Générer un QR code unique
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($colis) {
            if (empty($colis->qr_code)) {
                $colis->qr_code = $colis->numero_courrier;
            }
        });
    }

    /**
     * Accesseurs pour les statuts
     */
    public function getStatutLivraisonLabelAttribute()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'ramasse' => 'Ramassé',
            'en_transit' => 'En transit',
            'livre' => 'Livré'
        ];
        
        return $statuts[$this->statut_livraison] ?? 'Inconnu';
    }

    public function getStatutColorAttribute()
    {
        $colors = [
            'en_attente' => 'secondary',
            'ramasse' => 'warning',
            'en_transit' => 'info',
            'livre' => 'success'
        ];
        
        return $colors[$this->statut_livraison] ?? 'secondary';
    }

    /**
     * Générer les données JSON pour le QR Code
     */
    public function getQrDataAttribute()
    {
        return [
            'qr_code' => $this->qr_code,
            'numero_courrier' => $this->numero_courrier,
            'expediteur' => $this->nom_expediteur,
            'tel_expediteur' => $this->telephone_expediteur,
            'beneficiaire' => $this->nom_beneficiaire,
            'tel_beneficiaire' => $this->telephone_beneficiaire,
            'destination' => $this->destination,
            'montant' => $this->montant,
            'type_colis' => $this->type_colis,
            'agence_reception' => $this->agence_reception,
            'statut' => $this->statut_livraison,
            'created_at' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Générer le QR Code SVG
     */
    public function generateQrCode($size = 100)
    {
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
            ->format('svg')
            ->generate($this->numero_courrier);
    }

    /**
     * Générer le QR Code avec données complètes
     */
    public function generateDetailedQrCode($size = 150)
    {
        $data = json_encode($this->qr_data);
        return \SimpleSoftwareIO\QrCode\Facades\QrCode::size($size)
            ->format('svg')
            ->generate($data);
    }
}
