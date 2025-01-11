@extends('base')
@section('title', "Approvisionnement $depot->libele")

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
    

    <section class="p-10 gap-5 w-full">
     
    <section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
      <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Approvisionnement Produit {{$depot->libele}}</h2>
      <form  action="{{route('approStore', $depot->libele)}}" method="post">
        @method('post')
        @csrf
          <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
          <!-- <div class="sm:col-span-2">
                  <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Origine</label>
                  <select id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                      <option selected value="">Select Origine</option>
                      <option value="TV">Fournisseur</option>
                      <option value="PC">Ujisha</option>
                      <option value="GA">Samu</option>
                      <option value="PH">Entrepôt</option>
                  </select>
              </div> -->
              <!-- <div class="sm:col-span-2">
                  <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
                  <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
              </div> -->
              <div class="w-full" onclick="event.stopImmediatePropagation();">
                  <label for="produit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produit</label>
                  <input type="text" name="produit[]" 
                    id="autocompleteInput"
                    onkeyup="onkeyUp(event)" 
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Select produit" required>
                  <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                   
                </div>
              </div>
              <div class="w-full">
                  <label for="qte" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantité</label>
                  <input type="number" name="quantite[]" id="qte" min="1" max="1000" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="10" required>
              </div>

              <div id="dynamicForm"  class="mb-5 w-full sm:col-span-2 grid sm:grid-cols-2 sm:gap-6">
                    <div class="input-container mb-5">
                        <input type="text" id="assertionInput1" class="dynamic-input block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                         placeholder="Select produit" name="produit[]" >
                    </div>
                    <div class="input-container mb-5">
                        <input type="number" min="1" id="qteInput" class="dynamic-input block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                         placeholder="10" name="quantite[]" >
                    </div>
                </div>

              <!-- <div>
                  <label for="item-weight" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Item Weight (kg)</label>
                  <input type="number" name="item-weight" id="item-weight" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="12" required="">
              </div>  -->
              <!-- <div class="sm:col-span-2">
                  <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                  <textarea id="description" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Your description here"></textarea>
              </div> -->
          </div>
        <div class="flex justify-center m-5">
            <button type="submit"  class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Approvisionner</button>
        </div>

      </form>
  </div>
</section>
    </section>
    

    @include('composant.sidebar',['depot'=> $depot->libele])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    
let users = @json($produit);
console.log(users[0].libele)
function onkeyUp(e) {
  let keyword = e.target.value;
  let dropdownEl = document.querySelector("#dropdown");
  dropdownEl.classList.remove("hidden");
  let filteredusers = users.filter((c) =>
    c.name.toLowerCase().includes(keyword.toLowerCase())
  );

  renderOptions(filteredusers);
}

document.addEventListener("DOMContentLoaded", () => {
  renderOptions(users);
});

function renderOptions(options) {
  let dropdownEl = document.querySelector("#dropdown");

  let newHtml = ``;

  options.forEach((user) => {
    newHtml += `<div
      onclick="selectOption('${user.libele}')"
      class="px-5 py-3 border-b border-gray-200 text-stone-600 cursor-pointer hover:bg-slate-100 transition-colors"
    >
      ${user.libele}
    </div>`;
  });

  dropdownEl.innerHTML = newHtml;
}

function selectOption(selectedOption) {
  hideDropdown();
  let input = document.querySelector("#autocompleteInput");
  input.value = selectedOption;
}

document.addEventListener("click", () => {
  hideDropdown();
});

function hideDropdown() {
  let dropdownEl = document.querySelector("#dropdown");
  dropdownEl.classList.add("hidden");
}


//dynamic input
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('dynamicForm');
    

    form.addEventListener('input', function(event) {
        const inputs = document.querySelectorAll('.dynamic-input');
        const lastInput = inputs[inputs.length - 1];
        const num = inputs.length;

        // Vérifie si la valeur du dernier input n'est pas vide et s'il est le dernier input
        if (lastInput.value !== '' && lastInput !== inputs[inputs.length - 2]) {

            const newInputContainer = document.createElement('div');
            newInputContainer.className = "mb-5 input-container";

            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = "block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
            newInput.classList.add('dynamic-input');
            newInput.placeholder = 'Saisir la marque';
            newInput.name = `marque[]`;

;
            newInputContainer.appendChild(newInput);
            form.appendChild(newInputContainer);
        }
    });
});
</script>