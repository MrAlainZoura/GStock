@extends('base')
@section('title', "Tableau de bord")

@section('header')
  @include('composant.hearder', ['user_email'=>"$user->email", 'user_name'=>"$user->name"])
@endsection

@section('main')  
<section class=" p-2 gap-5 w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
    @foreach($cat as $key=>$v)
        @php 
            $marque=count($v->marque)
        @endphp
        @include('composant.cat', ['image'=>'ordi.jpg','libele'=>"$v->libele",'marque'=>"$marque",'tab'=>$v->marque])
    @endforeach   
    
    @include('composant.modal_cat',['libele'=>'Catégorie de produit', 'action'=>"cat-pro.store",'user_id'=> $user->id ]) 
   </section>
@endsection


@section('footer')
    @include('composant.footer')
@endsection
