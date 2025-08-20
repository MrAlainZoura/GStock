@extends('base')
@section('title', "Rapport mensuel ". session('depot'))

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
            <h1 class="mb-4 text-2xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Rapport Mensuel  {{session('depot')}} {{$mois}} </h1>
        </div>
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">1. VENTE </h1>
        </div>
        
        @include('composant.rapportTable',['data'=> $venteMois,"period"=>"Recette Mensuel"])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">2. APPROVISIONNEMENT </h1>
        </div>
        @include('composant.rapportTableAppro',['data'=> $approMois])
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. TRANSFERT </h1>
        </div>
        @include('composant.rapportTableTrans',['data'=> $transMois])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. RESUME STOCK </h1>
        </div>
            @include('composant.rapportTableResume',['data'=> $prodArrayResume])
    </section>

    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=> $depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

