@extends('base')
@section('title', "Tableau de bord")

@section('header')
  @include('composant.hearder', ['user_email'=>"$user->email", 'user_name'=>"$user->name"])
@endsection

@section('main')  

<section style="background-image: url('{{ asset('img/depot.jpg') }}');"  class="bg-center bg-no-repeat bg-cover bg-gray-700 bg-blend-multiply">
    <div class="px-4 mx-auto max-w-screen-xl text-center py-24 lg:py-56">
        <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-white md:text-5xl lg:text-6xl">Gérer votre Stock de produit en quelque clic</h1>
        <p class="mb-8 text-lg font-normal text-gray-300 lg:text-xl sm:px-16 lg:px-48">Choisez où vous voulez effectuer vos operations</p>
        <div class="flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-y-0">
            <a href="#" class="inline-flex justify-center items-center py-3 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                Commencons 
                {{$user->name}}
                <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
            <a href="#" class="inline-flex justify-center hover:text-gray-900 items-center py-3 px-5 sm:ms-4 text-base font-medium text-center text-white rounded-lg border border-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-400">
                A vous de jouer
            </a>  
        </div>
    </div>
</section>

<section class="mt-10 p-20 gap-5 w-full grid grid-cols-1 sm:grid-cols-2 ">

    <!-- Main modal -->
    @if($user->user_role->role->libele=='Administrateur')
        @foreach($user->depot as $cl=>$v)
            @include('composant.depot',['libele'=>"$v->libele", 'route'=>"depot/".$v->id*12726654])
        @endforeach  
        
    @elseif($user->user_role->role->libele=='Super admin')
        @foreach($depot as $cl=>$v)
            @include('composant.depot',['libele'=>"$v->libele", 'route'=>"depot/".$v->id*12726654])
        @endforeach
    @else
        @foreach($user->depotUser as $cl=>$v)
            @include('composant.depot',['libele'=>$v->depot->libele , 'route'=>"depot/".$v->depot->id*12726654])
        @endforeach
    @endif
    @include('composant.modal',['libele'=>'dépôt', 'action'=>"depot.store",'user_id'=> $user->id ]) 
   </section>
@endsection

@section('footer')
    @include('composant.footer')
@endsection


