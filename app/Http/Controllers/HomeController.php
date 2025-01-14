<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Produit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        //depot
        $depot = Depot::latest()->get();
        $user = auth()->user();
        return view('dashboard', compact('depot', 'user'));
    }
    public function home(){
        $produit = Produit::latest()->paginate(5);
        return view('home', compact('produit'));
    }

    
}
