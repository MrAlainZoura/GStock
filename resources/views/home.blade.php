@extends('base')
@section('title', "Home")

@section('header')
    @include('composant.nav')
@endsection

@section('main')
<section class="mt-10 p-20 gap-5 w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
<!-- @include('composant.card', ['image'=>'produit/pc.jpg','description'=>'hp probook G4 Ram 4G Rom 500G processeur 2.50G i3 8th','prix'=>'232 000Fc','depot'=>"samu"])
@include('composant.card', ['image'=>'produit/samA.jpg','description'=>'Samsung galaxy A1 ram 8 Rom 128G version 14','prix'=>'832 000Fc','depot'=>"samu"])
@include('composant.card', ['image'=>'produit/samsung.jpg','description'=>'Samsung Galaxy A2 ram 8 Rom 256G version 14','prix'=>'532 000Fc','depot'=>"samu"])
@include('composant.card', ['image'=>'produit/supphone.jpg','description'=>'Support telephone','prix'=>'532 000Fc','depot'=>"samu"]) -->

@foreach($produit as $key=>$value)
@include('composant.card', ['image'=>"storage/produit/$value->image",'description'=>$value->description,'prix'=>"$value->prix $", 'depot'=>"ujisha"])
@endforeach
<!-- Pagination -->

</section>
<div class="justify-between flex p-5 gap-5">{{ $produit->links() }} </div>
<section class="p-5 bg-gray-200">
    @if(session('echec'))
    <div id="alert-2" class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
  <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
  </svg>
  <span class="sr-only">Info</span>
  <div class="ms-3 text-sm font-medium">
    Les indentifiants incorrects, reessayer avec les bons
  </div>
  <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
    <span class="sr-only">Close</span>
    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
      <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
    </svg>
  </button>
</div>
    @endif
    <form action="{{route('login')}}" method="post" class="max-w-sm mx-auto">
        @csrf
        @method('post')
    <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre email</label>
        <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@gmail.com" required />
    </div>
    <div class="mb-5">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Votre mot de passe</label>
        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
    </div>
    <div class="flex items-start mb-5">
        <div class="flex items-center h-5">
        <input id="remember" type="checkbox" value="" class="hidden w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" />
        </div>
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
    </div>
    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Connexion</button>
    </form>
</section>
@endsection


@section('footer')
    @include('composant.footer')
@endsection