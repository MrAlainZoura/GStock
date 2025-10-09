<?php

namespace App\Exports;

use App\Models\ProduitDepot;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ProduitExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $depotId;

    public function __construct($depotId)
    {
        $this->depotId = $depotId;
    }

    public function collection()
    {
        return ProduitDepot::with('produit.marque.categorie')
        ->where('depot_id', $this->depotId)
        ->get()
        ->sortBy(function ($affectation) {
            return $affectation->produit->marque->categorie->libele ?? '';
        })
        ->map(function ($affectation) {
            $produit = $affectation->produit;

            return [
                'libele' => $produit->libele,
                'marque' => $produit->marque->libele ?? '',
                'categorie' => $produit->marque->categorie->libele ?? '',
                'quantite' => $affectation->quantite,
                'prix' => $produit->prix,
                'etat' => $produit->etat,
                'description' => $produit->description,
            ];
        });
    }


    public function headings(): array
    {
        return ['Libele', 'Marque', 'Categorie', 'Quantite', 'Prix', 'Etat', 'Description'];
    }

}
