@extends('base')
@section('title', "Create user")

@section('header')
  @include('composant.hearder', ['user_email'=>auth()->user()->id, 'user_name'=>auth()->user()->name])
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
    

    <section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Ajouter un Utilisateur</h2>
      <form action="{{route('user.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
              <div class="w-full flex flex-col sm:flex-row sm:col-span-2 sm:block"> 
              <img id="imagePreview" class="w-20 h-20 rounded-full m-2 border border-gray-300 " src="{{asset('svg/man.svg')}}" alt="Voir"> 
                <div class="w-full">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Prendre une image</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                        aria-describedby="file_input_help" 
                        id="file_input" type="file" name="image" accept=".jpg, .jpeg, .png, .gif, .jfif" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG ou GIF (MAX. 800x400px).</p>
                </div>                           
              </div>
              <div class="w-full">
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                  <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="nom" required="">
              </div>
              <div class="w-full">     
                  <label for="postnom" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Postnom</label>
                  <input type="text" name="postnom" id="postnom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="postnom" >
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
              <div>
                <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date de naissance</label>
                <div class="flex max-w-sm">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                        </svg>
                    </span>
                    <input name="naissance" id="datepicker-actions" datepicker datepicker-buttons datepicker-autoselect-today type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-none rounded-e-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="MM / JJ / AAAA">
                </div>
              </div> 
              <div class="w-full">
                  <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                  <input type="email" name="email" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="email@gstock.cd" required="">
              </div>
              <div class="w-full">
                  <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fonction</label>
                  <input type="text" name="fonction" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Livreur" required="">
              </div>
              <div>
                  <label for="shop" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Affectation</label>
                  <select required name="depot_id" id="shop" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected value="">Choisir le shop</option>
                        @foreach ($depot as $itm )
                            <option value="{{$itm->id}}">{{$itm->libele}}</option>
                        @endforeach  
                  </select>
              </div>
              <div class="w-full">
                  <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Niveau d'étude</label>
                  <input type="text" name="niveauEtude"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="D6, G3, L2, Master" >
              </div>
              <div class="w-full">
                  <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Option</label>
                  <input type="text" name="option"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Informatique,..." >
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
</section>

@include('composant.sidebar',['depot'=>session('depot'), 'depot_id'=>session('depot_id')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
  
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
});
</script>