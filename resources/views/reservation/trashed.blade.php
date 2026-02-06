@extends('base')
@section('title', "Liste de vente")

@section('header')
  @include('composant.hearder', ['user_email'=>Auth::user()->email, 'user_name'=>Auth::user()->name])
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
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Corbeille de Vente supprimée de {{$depot->libele}} </h1>
        </div>
        @include('composant.dataTableVenteTrashed',["data"=>$vente, 'deviseList'=>$depot->devise])
    </section>
    

    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=>$depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

