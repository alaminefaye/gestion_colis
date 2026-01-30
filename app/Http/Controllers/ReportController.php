<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colis;
use App\Models\Bagage;
use App\Models\Livreur;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all'); // 'colis', 'bagage', 'all'

        // Base queries
        $colisQuery = Colis::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $bagageQuery = Bagage::query()
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Apply Status Filter
        if ($status !== 'all') {
            if ($status === 'livre') {
                $colisQuery->where('statut_livraison', 'livre');
            } elseif ($status === 'en_attente') {
                $colisQuery->whereIn('statut_livraison', ['en_attente', 'expedie']);
            } elseif ($status === 'ramasse') {
                $colisQuery->where('statut_livraison', 'ramasse');
            }
            
            // Exclure les bagages lors du filtrage par statut car ils n'ont pas de champ statut
            $bagageQuery->whereRaw('1 = 0');
        }

        // Fetch Data
        $colis = ($type === 'all' || $type === 'colis') ? $colisQuery->get() : collect([]);
        $bagages = ($type === 'all' || $type === 'bagage') ? $bagageQuery->get() : collect([]);

        // Calculate Totals
        $totalColis = $colis->count();
        $totalBagages = $bagages->count();
        
        $totalMontantColis = $colis->sum('montant');
        $totalMontantBagages = $bagages->sum('montant');
        
        $colisLivres = $colis->where('statut_livraison', 'livre')->count();
        $colisRamasses = $colis->where('statut_livraison', 'ramasse')->count();
        $colisEnAttente = $colis->whereIn('statut_livraison', ['en_attente', 'expedie'])->count();

        return view('reports.index', compact(
            'colis', 
            'bagages', 
            'startDate', 
            'endDate', 
            'status', 
            'type',
            'totalColis',
            'totalBagages',
            'totalMontantColis',
            'totalMontantBagages',
            'colisLivres',
            'colisRamasses',
            'colisEnAttente'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');
        $type = $request->input('type', 'all');

        $fileName = 'rapport_activite_' . date('Y-m-d_H-i') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use ($startDate, $endDate, $status, $type) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel utf-8 compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Header
            fputcsv($file, array('Type', 'Code', 'Date', 'Expéditeur', 'Destinataire', 'Statut', 'Montant'));

            // Colis Data
            if ($type === 'all' || $type === 'colis') {
                $colisQuery = Colis::query()
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
                
                if ($status !== 'all') {
                     if ($status === 'livre') {
                        $colisQuery->where('statut_livraison', 'livre');
                    } elseif ($status === 'en_attente') {
                        $colisQuery->whereIn('statut_livraison', ['en_attente', 'expedie']);
                    } elseif ($status === 'ramasse') {
                        $colisQuery->where('statut_livraison', 'ramasse');
                    }
                }

                foreach ($colisQuery->get() as $colis) {
                    fputcsv($file, array(
                        'Colis',
                        $colis->code_suivi ?? $colis->id,
                        $colis->created_at->format('d/m/Y H:i'),
                        $colis->expediteur_nom ?? 'N/A',
                        $colis->destinataire_nom ?? 'N/A',
                        ucfirst($colis->statut_livraison),
                        $colis->montant
                    ));
                }
            }

            // Bagage Data
            if ($type === 'all' || $type === 'bagage') {
                $bagageQuery = Bagage::query()
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
                
                foreach ($bagageQuery->get() as $bagage) {
                    fputcsv($file, array(
                        'Bagage',
                        $bagage->code_suivi ?? $bagage->id,
                        $bagage->created_at->format('d/m/Y H:i'),
                        $bagage->expediteur_nom ?? 'N/A',
                        $bagage->destinataire_nom ?? 'N/A',
                        ucfirst($bagage->statut ?? 'N/A'),
                        $bagage->montant
                    ));
                }
            }
            
            // Add Totals Row
            fputcsv($file, array('', '', '', '', '', 'TOTAL', '')); // Placeholder for calculation in Excel

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
