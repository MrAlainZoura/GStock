@extends('base')
@section('title', "Tableau de bord")

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

        <section class="min-h-screen flex flex-col">
        <div class="p-5 flex justify-center items-center w-full flex-grow">
            <div class="p-4 w-full max-h-full ">
                <div class="rounded-lg shadow-md dark:bg-gray-700">
                    <div class="bg-red-100 flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Ajouter produit
                        </h3>
                        <button type="button" id="importBtn" onclick="importProduitExcel()" class="inline-flex items-center gap-2 justify-center p-1 text-base font-medium text-gray-500 rounded-lg  hover:text-black border border-green-700 hover:bg-green-300">
                            <svg width="24px" height="24px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                <g id="SVGRepo_iconCarrier">
                                <title>Fichier Excel</title>
                                <path d="M28.781,4.405H18.651V2.018L2,4.588V27.115l16.651,2.868V26.445H28.781A1.162,1.162,0,0,0,30,25.349V5.5A1.162,1.162,0,0,0,28.781,4.405Zm.16,21.126H18.617L18.6,23.642h2.487v-2.2H18.581l-.012-1.3h2.518v-2.2H18.55l-.012-1.3h2.549v-2.2H18.53v-1.3h2.557v-2.2H18.53v-1.3h2.557v-2.2H18.53v-2H28.941Z" style="fill:#20744a;fill-rule:evenodd"/>
                                <rect x="22.487" y="7.439" width="4.323" height="2.2" style="fill:#20744a"/>
                                <rect x="22.487" y="10.94" width="4.323" height="2.2" style="fill:#20744a"/>
                                <rect x="22.487" y="14.441" width="4.323" height="2.2" style="fill:#20744a"/>
                                <rect x="22.487" y="17.942" width="4.323" height="2.2" style="fill:#20744a"/>
                                <rect x="22.487" y="21.443" width="4.323" height="2.2" style="fill:#20744a"/>
                                <polygon points="6.347 10.673 8.493 10.55 9.842 14.259 11.436 10.397 13.582 10.274 10.976 15.54 13.582 20.819 11.313 20.666 9.781 16.642 8.248 20.513 6.163 20.329 8.585 15.666 6.347 10.673" style="fill:#ffffff;fill-rule:evenodd"/>
                                </g>
                            </svg>                            
                            <span class="w-full" id="spanImport">Importer depuis Excel</span>
                        </button> 

                        <a href=" {{route('depot.show', $depot_id*12726654)}} " class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </a>
                    </div>
                    <div class="p-4 md:p-5 bg-white rounded-lg">
                    <form class="rounded-lg" id="formSimple" action="{{route('prod.store')}}" method="post" enctype="multipart/form-data">
                            @csrf 
                            @method('post')
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <div class="mb-5">
                                        <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner Categorie produit</label>
                                        <select required name="" id="categories" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected value="0">Choisir une categorie</option>
                                            @foreach($tab as $k=>$v)
                                            <option value="{{$k}}">{{$v->libele}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-5" id="container_select">
                                        <label for="countries" id="label_marque" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner Marque produit</label>
                                        <select required name="marque_id" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option selected value="">Choisir une marque</option>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="hidden">
                                <input type="text" name="depot_id" value="{{$depot_id}}" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"  />
                            </div>
                            

                            <div class="inline-flex items-center justify-center w-full">
                                <hr class="w-64 h-1 my-8 bg-gray-200 border-0 rounded dark:bg-gray-700">
                                <div class="absolute px-4 -translate-x-1/2 bg-white left-1/2 dark:bg-gray-900">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 14">
                                        <path d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z"/>
                                    </svg>
                                </div>
                            </div>

                            
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div class="mb-5">
                                <label for="assertionInput1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Libele de produit
                                </label>
                                <input type="text"  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Saisir le nom de produit" name="libele" required>
                            </div>
                            <div class="mb-5">
                                <label for="assertionInput1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Prix en $
                                </label>
                                <input type="number"  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Saisir le nom de produit" name="prix" required min="0" >
                            </div>
                            <div class="mb-5">
                                <label for="assertionInput1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Quantité
                                </label>
                                <input type="text"  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Saisir le nom de produit" name="quantite">
                            </div>
                            <div class="mb-5">
                                <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Etat
                                </label>
                                <input type="text"  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Saisir le nom de produit" name="etat" required>
                            </div>
                            <div class="mb-5">
                                                        
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Prendre une image</label>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                                    aria-describedby="file_input_help" 
                                    id="file_input" type="file" name="image" accept=".jpg, .jpeg, .png, .gif, .jfif" />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG ou GIF (MAX. 800x400px).</p>
                                <img id="imagePreview" class="w-20 h-20 rounded" src="#" alt="Voir">
                            </div>
                            <div class="mb-5">
                                
                                <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea id="message" rows="4" 
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                name="description"
                                placeholder="Details du produit..."></textarea>

                            </div>
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" 
                                    class="w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Créer Produit
                                </button>
                            </div>

                        </form>
                        <form class="rounded-lg" id="formImport" action="{{route('import_prod_excel')}}" method="post" enctype="multipart/form-data">
                                @csrf 
                                @method('post')
                            <div id="alert-additional-content-2" class="p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                                <div class="flex items-center">
                                    <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                                    </svg>
                                    <span class="sr-only">Info</span>
                                    <h3 class="text-lg font-medium">Notice importante sur l'importation</h3>
                                </div>
                                <div class="mt-2 mb-4 text-sm">
                                    Rassurez-vous que votre tableau excel ait les différentes colonnes telle que le tableau ci-dessous, les colonnes libele et prix sont obligatoires. Quant aux autres colonnes la valeur par défaut sera "Divers" et zéro (0) pour la quantité si vous n'insérez aucune valeur.                              
                                </div>
                            </div>
                            <div class="relative overflow-x-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Libele
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Marque
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Categorie
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Quantite
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Prix
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Etat
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Description
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white dark:bg-gray-800">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                Apple MacBook Pro 17"
                                            </th>
                                            <td class="px-6 py-4">
                                                Apple
                                            </td>
                                            <td class="px-6 py-4">
                                                Ordinateur
                                            </td>
                                            <td class="px-6 py-4">
                                                1000
                                            </td>
                                            <td class="px-6 py-4">
                                                1000
                                            </td>
                                            <td class="px-6 py-4">
                                                Neuf ou reconditionné
                                            </td>
                                            <td class="px-6 py-4">
                                                Détails du produit
                                            </td>
                                        </tr>                                        
                                    </tbody>
                                </table>
                            </div>
                                
                            <div class="hidden">
                                <input type="password" name="depot_id" value="{{$depot_id}}" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"  />
                            </div>
                            <div class="inline-flex items-center justify-center w-full">
                                <hr class="w-64 h-1 my-8 bg-gray-200 border-0 rounded dark:bg-gray-700">
                                <div class="absolute px-4 -translate-x-1/2 bg-white left-1/2 dark:bg-gray-900">
                                    <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 14">
                                        <path d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z"/>
                                    </svg>
                                </div>
                            </div>                            
                            <div class="items-center flex justify-center">
                                <div class="mb-5">                      
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Importer un fichier Excel</label>
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                                        aria-describedby="file_input_help" 
                                        required
                                        id="" type="file" name="prodExcel" accept=".csv, .xls, .xlsx" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">CSV, XLS, XLSX</p>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <button type="submit" 
                                    class="w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Importer Produit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
   

    @include('composant.sidebar',['depot'=>session('depot')])
@endsection


@section('footer')
    @include('composant.footer')
@endsection

        <script>
            document.addEventListener("DOMContentLoaded", (event) => {

                const imageInput = document.getElementById('file_input');
                const imagePreview = document.getElementById('imagePreview');
            
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
            
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            imagePreview.setAttribute('src', event.target.result);
                            imagePreview.classList.remove('hidden'); // Affiche l'image
                        }
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.classList.add('hidden'); // Cache l'image si aucun fichier
                    }
                });


                document.getElementById('categories').addEventListener('change', function() {
                        // Récupérer l'élément select
                        var select = document.getElementById('categories');
                        // Récupérer la valeur sélectionnée
                        const valeur = parseInt (select.value);
                        const tabCab = @json($tab)
            
                        const options = tabCab[valeur].marque
                        const selectElement = document.createElement('select');
                            selectElement.name='marque_id'
                            selectElement.className = "bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    // Remplir le select avec les options du tableau
                    options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.id;  // Définir la valeur de l'option
                        optionElement.textContent = option.libele;  // Définir le texte affiché
                        selectElement.appendChild(optionElement);  // Ajouter l'option au select
                    });
                    const label_marque = document.getElementById('label_marque')
                    // Ajouter le select au conteneur dans le DOM
                    document.getElementById('container_select').innerHTML = '';
            
                    document.getElementById('container_select').appendChild(label_marque);
                    document.getElementById('container_select').appendChild(selectElement);
                    //    console.log(tabCab[valeur].marque, typeof(valeur), valeur)
                });

                let hide = true;
                const formImport = document.getElementById('formImport');
                      formImport.classList.add('hidden');
                const spanImport = document.getElementById('spanImport');
                      spanImport.textContent ="Importer depuis Excel";
                    
                 window.importProduitExcel = () => {
                    const importBtn = document.getElementById('importBtn'); 
                     const formSimple = document.getElementById('formSimple');
                    if (hide) {
                        formSimple.classList.add('hidden');
                        formImport.classList.remove('hidden');
                        spanImport.textContent ="Annuler Importation depuis Excel";

                    } else {
                        formImport.classList.add('hidden');
                        formSimple.classList.remove('hidden');
                        spanImport.textContent ="Importer depuis Excel";
                    }
                    hide = !hide; 
                    // console.log(hide)
                };
            });
        
        
                
        </script>