@extends('base')
@section('title', "Parametre")
<!-- <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}"> -->
<script src="{{asset('bootstrap/apexcharts.min.js')}}"></script>

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
    <div class=" w-full p-10">
        <div>
  <div class="px-4 sm:px-0">
    <h3 class="text-base/7 font-semibold text-gray-900">Information sur le depôt</h3>
    <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Details et parammètre.</p>
  </div>
  <div class="mt-6 border-t border-gray-100">
    <dl class="divide-y divide-gray-100">
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Nomination / Appellation</dt>
        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0"> {{session('depot')}} </dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Adresse / localisation </dt>
        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ "$depotData->pays - $depotData->province - $depotData->ville" }} <br>{{ $depotData->avenue }} </dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Contact / Email </dt>
        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ "$depotData->contact1, $depotData->contact" }} / {{$depotData->email}}</dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Personnel </dt>
        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{$collaborateur}} @if ($collaborateur>1) collaborateurs @else collaborateur @endif </dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Autre rensiegnement</dt>
        <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $depotData->autres }}</dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Monnaie & Devise</dt>
        <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
          <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200"> 
            @if ($depotData->devise != null)
              @foreach ( $depotData->devise as $val)
                <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">
                  <div class="flex w-0 flex-1 items-center">
                        <img src="{{asset('svg/paie.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                      <span class="truncate font-medium">1 {{ $val->libele }}</span>
                      <span class="shrink-0 text-gray-400">{{ $val->taux }} Franc (fc)</span>
                    </div>
                  </div>
                  <div class="ml-4 shrink-0">
                  <a href="{{ route('devise.update', ['depot'=>session('depot'), 'devise'=> $val->id*145]) }}" id="editeDevise" 
                   devise ="{{ $val->libele }}"
                   devise_id ="{{ $val->id }}"
                   taux ="{{ $val->taux }}"
                   title="{{ $val->libele  }}"
                   data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                   class="font-medium text-indigo-600 hover:text-indigo-500">
                   Editer
                  </a>
                </div>
                </li>
              @endforeach
            @endif          
          </ul>
        </dd>
      </div>

      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Attachments</dt>
        <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
          <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
            <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">
              <div class="flex w-0 flex-1 items-center">
                <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z" clip-rule="evenodd" />
                </svg>
                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                  <span class="truncate font-medium">Numéro national : </span>
                  <span class="shrink-0 text-gray-400">{{ $depotData->idNational }}</span>
                </div>
              </div>
              <!-- <div class="ml-4 shrink-0">
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
              </div> -->
            </li>
            <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">
              <div class="flex w-0 flex-1 items-center">
                <svg class="size-5 shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                  <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 0 0-4.242 0l-7 7a3 3 0 0 0 4.241 4.243h.001l.497-.5a.75.75 0 0 1 1.064 1.057l-.498.501-.002.002a4.5 4.5 0 0 1-6.364-6.364l7-7a4.5 4.5 0 0 1 6.368 6.36l-3.455 3.553A2.625 2.625 0 1 1 9.52 9.52l3.45-3.451a.75.75 0 1 1 1.061 1.06l-3.45 3.451a1.125 1.125 0 0 0 1.587 1.595l3.454-3.553a3 3 0 0 0 0-4.242Z" clip-rule="evenodd" />
                </svg>
                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                  <span class="truncate font-medium">Numéro impot : </span>
                  <span class="shrink-0 text-gray-400">{{ $depotData->numImpot }}</span>
                </div>
              </div>
              <!-- <div class="ml-4 shrink-0">
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Download</a>
              </div> -->
            </li>
          </ul>
        </dd>
      </div>
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-900">Apperçu Produits</dt>
        <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
          <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
            <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">
              <div class="flex w-0 flex-1 items-center">
                <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                  <span class="truncate font-medium">Catégorie disponible</span>
                  <span class="shrink-0 text-gray-400">{{count($tabCatMark)}}</span>
                </div>
              </div>
             
            </li>
            <li class="flex items-center justify-between py-4 pr-5 pl-4 text-sm/6">
              <div class="flex w-0 flex-1 items-center">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                  <span class="truncate font-medium">Marque disponible </span>
                  <span class="shrink-0 text-gray-400">{{$countMark}}</span>
                </div>
              </div>
             
            </li>
          </ul>
        </dd>
      </div>

    </dl>
  </div>
</div>

    </div>
    @include('composant.sidebar',['depot'=> session('depot')])

<!-- Main modal Edite Devise -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="textEditeDevise">
                    Editer Devise 
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="#" method="post" id="formDeviseUpdate">
                   @csrf 
                   @method('put')
                    <div>
                        <label for="deviseLibele" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Devise</label>
                        <input type="text" name="devise" id="deviseLibele" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" required />
                    </div>
                    <div>
                        <label for="tauxEchange" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Taux d'échange</label>
                        <input type="number" step="any" min="1" name="taux" id="tauxEchange"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Mettre à jour</button>
                    
                </form>
            </div>
        </div>
    </div>
</div> 


@endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
  document.addEventListener("DOMContentLoaded", (event) => {
    //modal suppression item
   const deleteLink = document.querySelectorAll('#editeDevise');

   deleteLink.forEach(link => {
   link.addEventListener('click', (event) => {
   event.preventDefault();
   const hrefClicked = event.currentTarget.getAttribute('href');
   const formDeviseUpdate =document.getElementById('formDeviseUpdate');
   const textDeleteItem =document.getElementById('textEditeDevise');
   const deviseLibele =document.getElementById('deviseLibele');
   const tauxEchange =document.getElementById('tauxEchange');
  
   const devise = event.currentTarget.getAttribute('devise') ;
   const devise_id = event.currentTarget.getAttribute('devise_id') ;
   const taux = event.currentTarget.getAttribute('taux') ;

  //  console.log('devise',devise, formDeviseUpdate, hrefClicked, taux, devise, devise_id)
   
   textDeleteItem.textContent= `Editer Devise [ ${devise} ]`;
   tauxEchange.value = taux;
   deviseLibele.value = devise;
   formDeviseUpdate.setAttribute('action',hrefClicked);
 });
});
  });
</script>