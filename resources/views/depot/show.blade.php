@extends('base')
@section('title', "Accueil")

@section('header')
  @include('composant.hearder', ['user_email'=>"$user->email", 'user_name'=>"$user->name"])
@endsection

@section('main')  

    @if(session('success'))
    <div class="alert-success">
    @include('composant.alert_suc', ['message'=>session('success')])
    </div>
    @endif
    @if(session('echec'))
    <div class="alert-echec">
        @include('composant.alert_echec', ['message'=>session('echec')])
    </div>
    @endif
    

   

    <div class=" w-full p-10">
        <div class="p-4 w-full rounded-lg  ">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="flex gap-4 p-2 items-center justify-center h-24 rounded-sm bg-blue-500">
                    <p class="text-2xl ">
                        Total vente du mois
                    </p>
                    <p class="flex items-center p-2 justify-center text-xl w-auto h-10  rounded-full ring-2 ring-gray-300 dark:ring-gray-500">
                        {{$depot->totalVente}}
                    </p>
                </div>
                <div class="flex gap-4 p-2 items-center justify-center h-24 rounded-sm bg-green-500 ">
                    <p class="text-2xl ">
                        Total approvisionnement du mois
                    </p>
                    <p class="flex items-center justify-center p-2 text-xl w-auto h-10  rounded-full ring-2 ring-gray-300 dark:ring-gray-500">
                       {{$depot->approMois}}
                    </p>
                </div>
                <div class="flex gap-4 p-2 items-center justify-center h-24 rounded-sm bg-red-100 ">
                    <p class="text-2xl ">
                        Total transfert du mois
                    </p>
                    <p class="flex items-center justify-center p-2 text-xl w-auto h-10  rounded-full ring-2 ring-gray-300 dark:ring-gray-500">
                    {{$depot->transMois}}
                    </p>
                </div>
                
            </div>
            <div class="flex items-center justify-center h-48 mb-4 rounded-sm bg-gray-50 dark:bg-gray-800">
                <p class="text-2xl ">
                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                    </svg>
                    graphique
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="p-4 items-center justify-center rounded-sm bg-blue-300 ">
                    <p class="text-2xl ">
                    Vendeur du mois :
                    </p>
                    <p class="text-2xl"> 
                    @if (count($vendeurs)>0)
                    {{$vendeurs[0]->user->name}} 
                    {{$vendeurs[0]->user->postnom}} 
                    {{$vendeurs[0]->user->prenom}}
                     avec {{$vendeurs[0]->count}} ventes
                    @endif
                    </p>
                </div>
                <div class="p-4 items-center justify-center rounded-sm bg-yellow-100 ">
                    <p class="text-2xl ">
                        Produit le plus vendu :
                    </p>
                    <p class="text-2xl ">
                        @php
                            $i = 0;
                            foreach ($tabProdVendu as $key => $valuer) {
                                if ($i ==0) {
                                    echo  $key . ", avec : " . $valuer ." pieces venduess";
                                }
                                $i++;
                            }
                        @endphp     
                    </p>
                </div>
                <div class="p-4 items-center justify-center rounded-sm bg-blue-100">
                    <p class="text-2xl ">
                    Second vendeur du mois :
                    </p>
                    <p class="text-2xl">
                    @if (count($vendeurs)>1)
                    {{$vendeurs[1]->user->name}} 
                    {{$vendeurs[1]->user->postnom}} 
                    {{$vendeurs[1]->user->prenom}}
                     avec {{$vendeurs[1]->count}} ventes
                    @endif
                    </p>
                </div>
                <div class="p-4 items-center justify-center rounded-sm bg-yellow-50">
                    <p class="text-2xl ">
                        Second produit le plus vendu :
                    </p>
                    <p class="flex text-2xl">
                    @php
                        $i = 0;
                        if(count($tabProdVendu)>1){
                            foreach ($tabProdVendu as $key => $valuer) {
                                if ($i ==1) {
                                    echo  $key . ", avec : " . $valuer ." pieces venduess";
                                }
                                $i++;
                            }
                        }
                    @endphp  
                    </p>
                </div>
            </div>
            
            
        </div>
    </div>
    <section class="p-10">

    <div class="title"> 
       <h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-orange-600 from-blue-400">Liste de produit de {{$depot->libele}}</span></h1>
    </div> 
   
    @include('composant.dataTable',['data'=>$depot->produitDepot])
    @include('composant.sidebar',['depot'=> $depot->libele])

   </section>
@endsection


@section('footer')
    @include('composant.footer')
@endsection

