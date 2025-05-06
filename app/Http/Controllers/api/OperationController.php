<?php

namespace App\Http\Controllers;

use App\Models\Operation;
use App\Models\Produit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marque = Operation::latest()->get();
        return response()->json(['success'=>true, 'data'=>$marque]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $operation = Operation::where("id",$id)->get();
        return response()->json(['success'=>true, 'data'=>$operation]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Operation $operation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Operation $operation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        //
    }
    public function vente(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'user_id'=>'required|exists:users,id',
            'depot_id'=>'required|exists:depots,id',
            'produit_id'=>'required|exists:produit,id',
            'quantite'=>'required',
            'destinationDepot'=>'required|exists:depots,id',
            'receptionUser'=>'required|exists:users,id',
            'libele'=>'required',
            'total'=>'int'
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $getPrix = Produit::where('id',$request->produit_id)->first();
        if(!$getPrix){
            $prix = $getPrix->prix * $request->quantite;
        }
        $prix = $request->total;
        $data = [
            'user_id'=>$request->user_id,
            'depot_id'=>$request->depot_id,
            'produit_id'=>$request->produit_id,
            'quantite'=>$request->quantite,
            'libele'=>$request->libele,
            'netPayer'=>$prix,
            'client'=>$request->client,
            'tel'=>$request->tel
        ];

        $verifStockDispo = Operation::where('depot_id', $request->depot_id)->where('libele','approvionnement')->get();

        $verifStockSorti = Operation::where('depot_id', $request->depot_id)->where('libele','transfert')->get();
        $verifStockVente = Operation::where('depot_id', $request->depot_id)->where('libele','vente')->get();

        $stockDispo =0;
        $stockSorti =0;
        foreach($verifStockDispo as $key=>$val){
            $stockDispo +=$val->quantite;
        }
        foreach($verifStockSorti as $k=>$v){
            $stockSorti +=$v->quantite;
        }
        foreach($verifStockVente as $cle=>$valeur){
            $stockSorti +=$valeur->quantite;
        }

        $faisabilite = $stockDispo - $stockSorti;
        if($faisabilite >= $request->quantite){

            try{
                $vente = Operation::create($data);
                if($vente){
                    $newQte = $getPrix->quantite - $request->quantite;
                    $getProdUpdate = Produit::where('id',$request->produit_id)->update(['quantite'=>$newQte]);
                }
            }catch(Exception $e){

            }
            return response()->json(['success'=>true, 'data'=>$vente]);
        }
        return response()->json(['success'=>false, 'msg'=>'Stock disponible insuffisant pour effectuer cette vente']);
    }
    public function transfert(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'user_id'=>'required|exists:users,id',
            'depot_id'=>'required|exists:depots,id',
            'produit_id'=>'required|exists:produit,id',
            'quantite'=>'required',
            'destinationDepot'=>'required|exists:depots,id',
            'receptionUser'=>'required|exists:users,id',
            'libele'=>'required'
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'user_id'=>$request->user_id,
            'depot_id'=>$request->depot_id,
            'produit_id'=>$request->produit_id,
            'quantite'=>$request->quantite,
            'libele'=>$request->libele,
            'destinationDepot'=>$request->destinationDepot,
            'receptionUser'=>$request->receptionUser,
            'status'=>'En attente de confirmation'
        ];

        $verifStockDispo = Operation::where('depot_id', $request->depot_id)->where('libele','approvionnement')->get();

        $verifStockSorti = Operation::where('depot_id', $request->depot_id)->where('libele','transfert')->get();
        $verifStockVente = Operation::where('depot_id', $request->depot_id)->where('libele','vente')->get();

        $stockDispo =0;
        $stockSorti =0;
        foreach($verifStockDispo as $key=>$val){
            $stockDispo +=$val->quantite;
        }
        foreach($verifStockSorti as $k=>$v){
            $stockSorti +=$v->quantite;
        }
        foreach($verifStockVente as $cle=>$valeur){
            $stockSorti +=$valeur->quantite;
        }

        $faisabilite = $stockDispo - $stockSorti;
        if($faisabilite > $request->quantite){

            $transfert = Operation::create($data);
            return response()->json(['success'=>true, 'data'=>$transfert]);
        }
        return response()->json(['success'=>false, 'msg'=>'Stock disponible insuffisant pour effectuer ce transfert']);
    }

    public function approvionnement(Request $request)
    {
        $validateDate = Validator::make($request->all(),
        [
            'user_id'=>'required|exists:users,id',
            'depot_id'=>'required|exists:depots,id',
            'produit_id'=>'required|exists:produit,id',
            'quantite'=>'required',
            'libele'=>'required'
        ]);

        if($validateDate->fails()){
            return $validateDate->errors();
        }
        $data = [
            'user_id'=>$request->user_id,
            'depot_id'=>$request->depot_id,
            'produit_id'=>$request->produit_id,
            'quantite'=>$request->quantite,
            'libele'=>$request->libele
        ];

        $appro = Operation::create($data);
        if($appro){
            $getQte = Produit::where('id',$request->produit_id)->first();
            $newQte = $getQte->quantite - $request->quantite;
            $getQteUpdate = Produit::where('id',$request->produit_id)->update(['quantite'=>$newQte]);

        }
        return response()->json(['success'=>true, 'data'=>$appro]);
    }
  
}
