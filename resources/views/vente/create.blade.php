@extends('base')
@section('title', "Transfert ". session('depot'))

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
    <div class="p-10">

        <form action="{{route('venteStore',session('depot'))}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
              <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                  
                  <div class="w-full">
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                      <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="nom" required="">
                  </div>
                  <div class="w-full">
                      <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prenom</label>
                      <input type="text" name="prenom" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Christ" >
                  </div>
                  <div>
                      <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                      <select id="category" name="genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                          <option selected value="">Selection votre genre</option>
                          <option value="F">F</option>
                          <option value="M">M</option>
                      </select>
                  </div>
                 
                  <div class="w-full">
                      <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Téléphone</label>
                      <input type="text" name="tel" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="+243 ..." >
                  </div>
                  <div class="w-full">
                      <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adresse</label>
                      <input type="text" name="adresse"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Kin, Lemba, Salongo" required="">
                  </div>
              </div>
              
              <div class="flex justify-center m-5">
                <button type="submit"  class="btn-primary w-1/2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ajouter</button>
            </div>
          </form>
    </div>
    
    @include('composant.sidebar',['depot'=> session('depot')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection
