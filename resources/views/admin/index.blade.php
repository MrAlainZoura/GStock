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
    <a href="{{ route("admin.create") }}" class="inline-flex justify-center hover:text-gray-900 items-center py-3 px-5 sm:ms-4 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
        Ajouter un admin
    </a> <br>
    liste des admin
    <br>
    <!-- {{ $getAdminAll }} -->
    <ul>
        @foreach ( $getAdminAll as $admin)
           <li>
                {{  $admin->name}} {{ $admin->depot->count() }} depot
           </li> 
        @endforeach
    </ul>
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

