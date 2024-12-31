<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        //depot
        $depot = Depot::latest()->get();
        $user = auth()->user();
        return view('dashboard', compact('depot', 'user'));
    }
}
