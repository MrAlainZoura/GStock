@extends('base')
@section('title', "Tableau de bord")

@section('header')
  @include('composant.hearder', ['user_email'=>"$user->email", 'user_name'=>"$user->name"])
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
    @include('composant.sidebar')


    <section class="p-10 gap-5 w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
       
<!-- Main modal -->
<div id="authentication-modal2" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 max-w-30 max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ajouter produit
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal2">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="{{route($action)}}" method="post" enctype="multipart/form-data">
                    @csrf 
                    @method('post')
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div class="mb-5">
                                <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner Categorie {{$libele}}</label>
                                <select required name="" id="categories" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected value="0">Choisir une categorie</option>
                                    @foreach($tab as $k=>$v)
                                    <option value="{{$k}}">{{$v->libele}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-5" id="container_select">
                                <label for="countries" id="label_marque" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner Marque {{$libele}}</label>
                                <select required name="marque_id" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected value="">Choisir une marque</option>
                                </select>
                            </div>
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

                    <label for="" class="block mb-2 text-dm font-medium text-gray-900 dark:text-white">Ajouter produit</label>
                    
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
            </div>
        </div>
    </div>
</div> 

</section>
<section class="p-10">

@endsection


@section('footer')
    @include('composant.footer')
@endsection

        <script>
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
                
        </script>