<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Enum\DepotType;
use App\Models\Produit;
use App\Models\ProduitDepot;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        //depot
        Depot::where('type',"")->update(['type'=>"Shop"]);
        Produit::where('unite',"")->update(['unite'=>"pc"]);
        ProduitDepot::where('quantite',"<",0)->update(['quantite'=>0]);
        $depot = Depot::latest()->get();
        $depotType = DepotType::cases();
        $user = auth()->user();
        return view('dashboard', compact('depot', 'user', 'depotType'));
    }
    public function home(){
        $produit = Produit::latest()->paginate(10);
        return view('home', compact('produit'));
    }

    public function guide(){
        return view('use.index');
    }
    public function faq(){
        return view('use.faq');
    }

    
}
