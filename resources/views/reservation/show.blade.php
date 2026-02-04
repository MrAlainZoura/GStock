@extends('base')
@section('title', "Vente ")

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
    
    @include('composant.sectionVenteShow',['findVenteDetail'=>$detailVente])

    @include('composant.sidebar',['depot'=> $detailVente->depot->libele, 'depot_id'=>$detailVente->depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

