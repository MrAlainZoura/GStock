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
    

    <section class="p-10 gap-5 w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
        @foreach($cat as $key=>$v)
            @include('composant.cat', ['image'=>"$v->image" ,'libele'=>"$v->libele",'marque'=>count($v->marque),'tab'=>$v->marque])
        @endforeach   
        
        @include('composant.modal_cat',['libele'=>'Catégorie de produit', 'action'=>"cat-pro.store",'user_id'=> $user->id ]) 
        @include('composant.modal_prod',['libele'=>'Produit', 'action'=>"prod.store",'depot_id'=> $depot->id, 'tab'=>$cat ]) 
    </section>
    <section class="p-10">

  
    <div class="title"> 
       <h1 class="mb-4 text-3xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-6xl"><span class="text-transparent bg-clip-text bg-gradient-to-r to-orange-600 from-blue-400">Liste de produit de {{$depot->libele}}</span></h1>
    </div> 
   
    @include('composant.dataTable',['data'=>$depot->produitDepot])
   </section>

   @include('composant.sidebar',['depot'=> $depot->libele])
@endsection


@section('footer')
    @include('composant.footer')
@endsection

