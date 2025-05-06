@extends('base')
@section('title', "Liste utilisateurs")

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
     
        @include('composant.dataTableUser', ['data'=>$user])
       
    </section>
    

    @include('composant.sidebar',['depot'=> session('depot')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

