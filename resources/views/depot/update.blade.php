@extends('base')
@section('title', "Mise à jour  ".session('depot'))
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
                <h3 class="text-base/7 font-semibold text-gray-900">Mise à jour des Informations sur le depôt {{ $getDepotInformation->libele }}</h3>
                <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Details et parammètre.</p>
            </div>
        </div>
        <form method="post" action="{{ route('depot.update', session('depot')) }}" enctype="multipart/form-data">
          @method('put')
          @csrf
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
              <h2 class="text-base/7 font-semibold text-gray-900">Profile</h2>
              <p class="mt-1 text-sm/6 text-gray-600">Certaines informations ici seront publiques.</p>

              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                
                <div class="">
                  @foreach ($getDepotInformation->devise as $k=>$veleur)
                      <div class="flex flex-1 items-center">
                        <img src="{{asset('svg/paie.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500  group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"  alt="">
                      <div class="ml-4 flex min-w-0 flex-1 gap-2">
                        <span class=" font-medium">1 {{ $veleur->libele }} :</span>
                        <span class="shrink-0 text-gray-600">{{ $veleur->taux }} Fc</span>
                      </div>
                  </div>
                  @endforeach
                </div>
                <div class="col-span-full">
                  <label for="libele" class="block text-sm/6 font-medium text-gray-900">Appellation de Dépôt</label>
                  <div class="mt-2">
                    <input type="text" name="libele" id="libele" value="{{ $getDepotInformation->libele }}" autocomplete="off" class="block w-1/3 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
                <div class="col-span-full">
                  <label for="ets" class="block text-sm/6 font-medium text-gray-900">Entreprise ou Société mère</label>
                  <div class="mt-2">
                    <input type="text" id="ets" name="codeP" value="{{ $getDepotInformation->cpostal }}" autocomplete="off" class="block w-1/3 rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
                <div class="col-span-full">
                  <label for="photo" class="block text-sm/6 font-medium text-gray-900">Logo</label>
                  <div class="mt-2 flex items-center gap-x-3 ">
                    @if ($getDepotInformation->logo)
                      <img class="w-16 h-16 rounded-full" src="{{asset('uploads/logo/'.$getDepotInformation->logo)}}" alt="Large avatar">
                    @else
                    <svg class="size-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                    </svg>
                    @endif
                    <input 
                      class="block w-sm  text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                      id="small_size" 
                      type="file"
                      name="logo" 
                     accept=".jpeg,.png,.jpg">
                  </div>
                  
                </div>
              
              </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
              <h2 class="text-base/7 font-semibold text-gray-900">Information sur le droit d'exercice avec l'Etat</h2>

              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 border-b border-gray-900/10 pb-12">
                <div class="sm:col-span-3">
                  <label for="idNat" class="block text-sm/6 font-medium text-gray-900">Numéro d'identification national</label>
                  <div class="mt-2">
                    <input type="text" name="idNat" value="{{ $getDepotInformation->idNational }}" id="idNat" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
                <div class="sm:col-span-3">
                  <label for="impot" class="block text-sm/6 font-medium text-gray-900">Numéro d'impôt</label>
                  <div class="mt-2">
                    <input type="text" name="impot" id="impot" value="{{ $getDepotInformation->numImpot }}" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
              </div>
              <h2 class="mt-10 text-base/7 font-semibold text-gray-900">Ajouter une monaie</h2>
              <!-- <p class="mt-1 text-sm/6 text-gray-600">Use a permanent address where you can receive mail.</p> -->

              <div id="divMonnaie" class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 border-b border-gray-900/10 pb-12">
                <div class="sm:col-span-3">
                  <label for="monnaie" class="block text-sm/6 font-medium text-gray-900">Devise</label>
                  <div class="mt-2">
                    <input type="text" name="monnaie" id="monnaie" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
                <div class="sm:col-span-3">
                  <label for="tauxDEchange" class="block text-sm/6 font-medium text-gray-900">Taux d'échange</label>
                  <div class="mt-2">
                    <input type="text" name="tauxDEchange" id="tauxDEchange" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
              </div>
              <h2 class="text-base/7 font-semibold text-gray-900 mt-5">Information sur le contact et localisation</h2>

              <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 ">
                <div class="sm:col-span-3">
                  <label for="numPrincipal" class="block text-sm/6 font-medium text-gray-900">Numéro principal</label>
                  <div class="mt-2">
                    <input type="text" name="numPrincipal" id="numPrincipal" value="{{ $getDepotInformation->contact }}" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>

                <div class="sm:col-span-3">
                  <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Numéro Secondaire</label>
                  <div class="mt-2">
                    <input type="text" name="numSecond" id="last-name" value="{{ $getDepotInformation->contact }}" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
                <div class="sm:col-span-4">
                  <label for="email" class="block text-sm/6 font-medium text-gray-900">Adresse Email</label>
                  <div class="mt-2">
                    <input id="email" name="email" type="email" value="{{ $getDepotInformation->email }}" autocomplete="email" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>

                <div class="sm:col-span-3">
                  <label for="pays" class="block text-sm/6 font-medium text-gray-900">Pays</label>
                  <div class="mt-2 grid grid-cols-1">
                    <select id="pays" name="pays" value="{{$getDepotInformation->pays }}" autocomplete="country-name" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      
                    </select>
                    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>

                <div class="col-span-full">
                  <label for="avenue" class="block text-sm/6 font-medium text-gray-900">Adresse avenue</label>
                  <div class="mt-2">
                    <input type="text" name="avenue" id="avenue" value="{{ $getDepotInformation->avenue }}" autocomplete="off" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>

                <div class="sm:col-span-2 sm:col-start-1">
                  <label for="city" class="block text-sm/6 font-medium text-gray-900">Ville</label>
                  <div class="mt-2">
                    <input type="text" name="ville" value="{{ $getDepotInformation->ville }}" id="city" autocomplete="address-level2" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>

                <div class="sm:col-span-2">
                  <label for="region" class="block text-sm/6 font-medium text-gray-900">Etat / Province</label>
                  <div class="mt-2">
                    <input type="text" name="province" value="{{ $getDepotInformation->province }}" id="region" autocomplete="address-level1" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>

              </div>
            </div>

            <div class="border-b border-gray-900/10 pb-12">
              <h2 class="text-base/7 font-semibold text-gray-900">Autres détails</h2>
              <div class="sm:col-span-2">
                  <label for="remboursement_delay" class="text-base/7 font-semibold text-gray-900">Delait de remboursement</label>
                  <div class="mt-2">
                    <input type="text" name="remboursement_delay" value="{{ $getDepotInformation->remboursement_delay }}" id="remboursement_delay" autocomplete="" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>
                </div>
              <p class="text-base/7 font-semibold text-gray-900">Information facultative du dépôt en bas de la facure pour la garentie.</p>
              <div class="mt-2">
                    <textarea name="autres" id="about" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                      {{ $getDepotInformation->autres }}
                    </textarea>
              </div>
          
            </div>
          </div>

          <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="text-sm/6 font-semibold text-gray-900">Annuler</button>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sauvegarder</button>
          </div>
        </form>

    </div>
    @include('composant.sidebar',['depot'=> $getDepotInformation->libele, 'depot_id'=>$getDepotInformation->id])

@endsection


@section('footer')
    @include('composant.footer')
@endsection

  <script>
    document.addEventListener("DOMContentLoaded", function () {

        const pays = [
          "Afghanistan", "Afrique du Sud", "Albanie", "Algérie", "Allemagne", "Andorre", "Angola",
          "Arabie Saoudite", "Argentine", "Arménie", "Australie", "Autriche", "Azerbaïdjan",
          "Belgique", "Bénin", "Bhoutan", "Biélorussie", "Bolivie", "Bosnie-Herzégovine", "Botswana",
          "Brésil", "Bulgarie", "Burkina Faso", "Burundi", "Cameroun", "Canada", "Chili", "Chine",
          "Chypre", "Colombie", "Comores", "Congo (Brazzaville)", "Congo (Kinshasa)", "Corée du Sud",
          "Costa Rica", "Côte d'Ivoire", "Croatie", "Cuba", "Danemark", "Djibouti", "Égypte", "Émirats arabes unis",
          "Équateur", "Espagne", "Estonie", "États-Unis", "Éthiopie", "Finlande", "France", "Gabon",
          "Gambie", "Géorgie", "Ghana", "Grèce", "Guatemala", "Guinée", "Haïti", "Honduras", "Hongrie",
          "Inde", "Indonésie", "Irak", "Iran", "Irlande", "Islande", "Israël", "Italie", "Japon", "Jordanie",
          "Kazakhstan", "Kenya", "Kirghizistan", "Koweït", "Laos", "Lettonie", "Liban", "Libéria", "Libye",
          "Lituanie", "Luxembourg", "Madagascar", "Malaisie", "Mali", "Malte", "Maroc", "Maurice", "Mauritanie",
          "Mexique", "Moldavie", "Monaco", "Mongolie", "Mozambique", "Namibie", "Népal", "Niger", "Nigéria",
          "Norvège", "Nouvelle-Zélande", "Oman", "Ouganda", "Ouzbékistan", "Pakistan", "Palestine", "Panama",
          "Paraguay", "Pays-Bas", "Pérou", "Philippines", "Pologne", "Portugal", "Qatar", "République Centrafricaine",
          "République Tchèque", "Roumanie", "Royaume-Uni", "Russie", "Rwanda", "Sénégal", "Serbie", "Singapour",
          "Slovaquie", "Slovénie", "Somalie", "Soudan", "Sri Lanka", "Suède", "Suisse", "Syrie", "Tadjikistan",
          "Tanzanie", "Tchad", "Thaïlande", "Togo", "Tunisie", "Turquie", "Ukraine", "Uruguay", "Venezuela",
          "Vietnam", "Yémen", "Zambie", "Zimbabwe", "Autres"
        ];
    
        const select = document.getElementById("pays");
    
        pays.forEach(p => {
          const option = document.createElement("option");
          option.value = p;
          option.textContent = p;
          select.appendChild(option);
        });

        let paysDefault = @json($getDepotInformation->pays);
        select.value = paysDefault ?? "Congo (Kinshasa)";

        // select.addEventListener('change', () => {
        //   console.log(select.value);
        // });

        const divMonnaie = document.getElementById("divMonnaie");
        
        let vrai = false;

        const verifChamp = () => {
          const champ1 = document.getElementById("monnaie");
          const champ2 = document.getElementById("tauxDEchange"); // Remplace avec l'ID réel
          const container = document.getElementById("divMonnaie");
          const valeur1 = champ1?.value?.trim();
          const valeur2 = champ2?.value?.trim();

              const divM = document.createElement('div');
                    divM.setAttribute('class', 'sm:col-span-3 divM');
                    divM.setAttribute('id', 'divM');
                    divM.innerHTML = `<label for="monnaie1" class="block text-sm/6 font-medium text-gray-900">Devise</label>
                    <div class="mt-2">
                    <input type="text" name="monnaie1" id="monnaie1" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                    </div>`
              const divT = document.createElement('div');
                          divT.setAttribute('class', 'sm:col-span-3 divT');
                          divT.id ='divT';
                    divT.innerHTML = `<label for="tauxDEchange1" class="block text-sm/6 font-medium text-gray-900">Taux d'échange</label>
                  <div class="mt-2">
                    <input type="text" name="tauxDEchange1" id="tauxDEchange1" autocomplete="given-name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                  </div>`;

          const conditionRemplie = valeur1 && valeur2;
          if (!conditionRemplie) {
            const taux1 = document.querySelector(".divT");
            const monnaie1 = document.querySelector(".divM");
            if (taux1) taux1.remove(); // Supprime l'élément du DOM
            if (monnaie1) monnaie1.remove(); // Supprime l'élément du DOM
            if(monnaie1) vrai = false; 
            
          } else {
            const taux1 = document.getElementById("divT");
            const monnaie1 = document.getElementById("divM");

          if(vrai!==true){ 
            // console.log(vrai, 'dans le if vrai')
            container.appendChild(divM);
            container.appendChild(divT);
            vrai = !vrai;
          }
        }   

        };
      document.getElementById("tauxDEchange").addEventListener("input", verifChamp);        
      document.getElementById("monnaie").addEventListener("input", verifChamp);        
    });
  </script>