<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProduitDepotController extends Controller
{
    public function doublon(){
        $doublons = DB::table('produit_depots')
            ->select('depot_id', 'produit_id', DB::raw('COUNT(*) as total'))
            ->groupBy('depot_id', 'produit_id')
            ->having('total', '>', 1)
            ->get();
        if($doublons->count()>0){
            foreach ($doublons as $item) {
            $ids = DB::table('produit_depots')
                ->where('depot_id', $item->depot_id)
                ->where('produit_id', $item->produit_id)
                ->orderBy('id') // du plus ancien au plus récent
                ->pluck('id');

            // On garde le premier ID, on supprime les autres
            $idsToDelete = $ids->slice(1); // saute le premier

            $rec = DB::table('produit_depots')
                    ->whereIn('id', $idsToDelete)
                    ->delete();
            }
            return response()->json(['success'=>true, 'data'=>$doublons]);
        }
        return response()->json(['success'=>true, 'data'=>null]);
    }
}
