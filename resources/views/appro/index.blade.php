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
    

    <section class="p-10 gap-5 w-full">
     
        @include('composant.dataTableAppro', ['data'=>$depot->approvisionnement])
       
    </section>
    

    @include('composant.sidebar',['depot'=> $depot->libele])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

