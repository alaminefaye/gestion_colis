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
        'recupere_par_user_id',
        'photo_piece_recto',
        'photo_piece_verso',
        'created_by',
        'receptionne_par',
        'receptionne_le',
        'notes_reception',
    ];

    protected $casts = [
        'montant' => 'integer',
        'valeur_colis' => 'integer',
        'ramasse_le' => 'datetime',
        'livre_le' => 'datetime',
        'recupere_gare' => 'boolean',
        'recupere_le' => 'datetime',
        'receptionne_le' => 'datetime',
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
     * Utilisateur qui a réceptionné le colis
     */
    public function receptionneParUser()
    {
        return $this->belongsTo(User::class, 'receptionne_par');
    }

    /**
     * Générer un numéro de courrier automatique au format: colis-NNNNNNN
     */
    public static function generateNumeroCourrier()
    {
        $prefix = 'colis-';
        
        // Récupérer tous les colis avec le nouveau format (colis-NNNNNNN)
        $colisList = self::where('numero_courrier', 'like', $prefix . '%')
                         ->where('numero_courrier', 'not like', $prefix . '%-%') // Exclure les anciens formats avec date
                         ->pluck('numero_courrier')
                         ->toArray();
        
        $maxNumero = 0;
        
        // Extraire le numéro maximum des colis existants
        foreach ($colisList as $numeroCourrier) {
            // Extraire le numéro après "colis-"
            $numeroStr = substr($numeroCourrier, strlen($prefix));
            // Vérifier que c'est un nombre
            if (is_numeric($numeroStr)) {
                $numero = (int) $numeroStr;
                if ($numero > $maxNumero) {
                    $maxNumero = $numero;
                }
            }
        }
        
        // Prochain numéro
        $nextNumero = $maxNumero + 1;
        
        // Générer le numéro avec padding de 7 chiffres
        $numero = str_pad($nextNumero, 7, '0', STR_PAD_LEFT);
        $numeroCourrier = $prefix . $numero;
        
        // Vérifier l'unicité (protection contre les collisions)
        $maxAttempts = 100;
        $attempts = 0;
        while (self::where('numero_courrier', $numeroCourrier)->exists() && $attempts < $maxAttempts) {
            $nextNumero++;
            $numero = str_pad($nextNumero, 7, '0', STR_PAD_LEFT);
            $numeroCourrier = $prefix . $numero;
            $attempts++;
        }
        
        return $numeroCourrier;
    }

    /**
     * Générer un QR code unique
     */
    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($colis) {
            // Générer automatiquement le numéro de courrier s'il n'est pas fourni
            if (empty($colis->numero_courrier)) {
                $colis->numero_courrier = self::generateNumeroCourrier();
            }
            
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
            'livre' => 'Livré',
            'receptionne' => 'Réceptionné'
        ];
        
        return $statuts[$this->statut_livraison] ?? 'Inconnu';
    }

    public function getStatutColorAttribute()
    {
        $colors = [
            'en_attente' => 'secondary',
            'ramasse' => 'warning',
            'en_transit' => 'info',
            'livre' => 'success',
            'receptionne' => 'primary'
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
    
    /**
     * Accesseur pour l'URL de la photo recto
     */
    public function getPhotoRectoUrlAttribute()
    {
        if ($this->photo_piece_recto) {
            return asset('storage/' . $this->photo_piece_recto);
        }
        return null;
    }
    
    /**
     * Accesseur pour l'URL de la photo verso
     */
    public function getPhotoVersoUrlAttribute()
    {
        if ($this->photo_piece_verso) {
            return asset('storage/' . $this->photo_piece_verso);
        }
        return null;
    }

    /**
     * Relation avec l'utilisateur qui a créé le colis
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
