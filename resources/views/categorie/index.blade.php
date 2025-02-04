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
    <div class="p-5 flex gap-5">
        @foreach($cat as $key=>$v)
            @include('composant.cat', ['image'=>"$v->image" ,'libele'=>"$v->libele",'marque'=>count($v->marque),'tab'=>$v->marque])
        @endforeach   
    </div>

    <section >        
        @include('composant.modal_cat',['libele'=>'Catégorie de produit', 'action'=>"cat-pro.store",'user_id'=> $user->id ]) 
    </section>
    @include('composant.sidebar',['depot'=> session('depot')])

@endsection


@section('footer')
    @include('composant.footer')
@endsection

