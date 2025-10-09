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
    
    <section class="p-10">
  
    <div class="title"> 
       <h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-orange-600 from-blue-400">Liste de produit de {{session('depot')}}</span></h1>
    </div> 
    @if(optional($prodDepot->first())->id)
    <div class="w-full flex justify-end m-5">
         <a href="{{route('export_prod_excel',(int)$prodDepot->first()->depot_id*12)}}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
             TELECHARGER EXCEL
             <img src="{{asset('svg/pdf.svg')}}" class="w-6 rounded" alt="">
         </a>
     </div>
    @endif
    @include('composant.dataTable',['data'=>$prodDepot])
   </section>
   <section >        
        @include('composant.modal_prod',['libele'=>'Produit', 'action'=>"prod.store",'depot_id'=> $depotData->id, 'tab'=>$cat ]) 
    </section>

   @include('composant.sidebar',['depot'=> $depotData->libele, 'depot_id'=>$depotData->id])
@endsection


@section('footer')
    @include('composant.footer')
@endsection

