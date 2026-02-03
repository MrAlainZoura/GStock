<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Enum\DepotType;
use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        //depot
        Depot::where('type',"")->update(['type'=>"Shop"]);
        Produit::where('unite',"")->update(['unite'=>"pc"]);
        $depot = Depot::latest()->get();
        $depotType = DepotType::cases();
        $user = auth()->user();
        return view('dashboard', compact('depot', 'user', 'depotType'));
    }
    public function home(){
        $produit = Produit::latest()->paginate(10);
        return view('home', compact('produit'));
    }

    
}
