<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colis;

class ColisQrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Générer des QR codes pour tous les colis qui n'en ont pas
        $colisWithoutQr = Colis::whereNull('qr_code')->get();
        
        foreach ($colisWithoutQr as $colis) {
            $colis->qr_code = 'COL-' . strtoupper(uniqid());
            $colis->save();
        }
        
        $this->command->info('QR codes générés pour ' . $colisWithoutQr->count() . ' colis.');
        
        // Créer quelques colis de test avec QR codes
        $testColis = [
            [
                'numero_courrier' => 'TEST001',
                'destination' => 'Dakar',
                'nom_expediteur' => 'Test Expéditeur 1',
                'telephone_expediteur' => '771234567',
                'nom_beneficiaire' => 'Test Bénéficiaire 1',
                'telephone_beneficiaire' => '781234567',
                'montant' => 15000,
                'valeur_colis' => 25000,
                'type_colis' => 'standard',
                'agence_reception' => 'Agence Dakar',
                'description' => 'Colis de test pour QR code',
                'qr_code' => 'COL-TEST001',
                'statut_livraison' => 'en_attente'
            ],
            [
                'numero_courrier' => 'TEST002',
                'destination' => 'Thiès',
                'nom_expediteur' => 'Test Expéditeur 2',
                'telephone_expediteur' => '771234568',
                'nom_beneficiaire' => 'Test Bénéficiaire 2',
                'telephone_beneficiaire' => '781234568',
                'montant' => 20000,
                'valeur_colis' => 35000,
                'type_colis' => 'express',
                'agence_reception' => 'Agence Thiès',
                'description' => 'Colis express de test',
                'qr_code' => 'COL-TEST002',
                'statut_livraison' => 'ramasse'
            ],
            [
                'numero_courrier' => 'TEST003',
                'destination' => 'Saint-Louis',
                'nom_expediteur' => 'Test Expéditeur 3',
                'telephone_expediteur' => '771234569',
                'nom_beneficiaire' => 'Test Bénéficiaire 3',
                'telephone_beneficiaire' => '781234569',
                'montant' => 12000,
                'valeur_colis' => 18000,
                'type_colis' => 'document',
                'agence_reception' => 'Agence Saint-Louis',
                'description' => 'Documents importants',
                'qr_code' => 'COL-TEST003',
                'statut_livraison' => 'livre'
            ]
        ];
        
        foreach ($testColis as $colisData) {
            Colis::firstOrCreate(
                ['numero_courrier' => $colisData['numero_courrier']], 
                $colisData
            );
        }
        
        $this->command->info('3 colis de test créés avec QR codes.');
    }
}
