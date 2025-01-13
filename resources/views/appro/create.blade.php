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
        <input type="hidden" name="depot_id" value="{{$depot->id}}">
        <input type="hidden" name="user_id" value="{{$user->id}}">
          <div class="grid gap-4 grid-cols-1 sm:gap-6">
          
              <div class="w-full sm:col-span-2" onclick="event.stopImmediatePropagation();">
                  <input type="text" 
                    id="autocompleteInput"
                    onkeyup="onkeyUp(event)" 
                    autocomplete="off"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Select produit">
                  <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                   
                </div>
              </div>
              <div class="grid grid-cols-2">
                  <div class="w-full">
                    <label for="produit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produit</label>
                  </div>
                  <div class="w-full">
                      <label for="qte" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantité</label>
                  </div>
              </div>
              <div id="dynamicForm"  class="mb-5 w-full sm:col-span-2 gap-4 grid grid-cols-2 sm:gap-6">
                    
              </div>

             
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
// console.log(users)
function onkeyUp(e) {
  let keyword = e.target.value;
  let dropdownEl = document.querySelector("#dropdown");
  dropdownEl.classList.remove("hidden");

  let filteredusers = users.filter((c) =>
    // {return users.every(filter => {
    //   return c.description.toLowerCase().includes(keyword.toLowerCase());
    // });}
    c.description.toLowerCase().includes(keyword.toLowerCase())
  );

  renderOptions(filteredusers);
}

document.addEventListener("DOMContentLoaded", () => {
  renderOptions(users);
});

function renderOptions(options) {
  let dropdownEl = document.querySelector("#dropdown");

  let newHtml = ``;

  options.forEach((user,indice) => {
    newHtml += `<div
      onclick="selectOption('${user.marque.libele}', '${user.id}', '${indice}')"
      class="px-5 py-3 border-b border-gray-200 text-stone-600 cursor-pointer hover:bg-slate-100 transition-colors"
    >
      ${user.marque.libele} ${user.libele}
    </div>`;

  });
  dropdownEl.innerHTML = newHtml;
}

function selectOption(selectedOption, inputValue, indeX) {
  hideDropdown();
  let input = document.querySelector("#autocompleteInput");
  input.value = '';
  // console.log(input.value, 'test', indeX)

  const form = document.getElementById('dynamicForm');
  const inputs = document.querySelectorAll('.dynamic-input');
  const newInputContainer = document.createElement('div');
    newInputContainer.className = "mb-5 input-container";

  const newInputNumber = document.createElement('input');
    newInputNumber.type = 'number';
    newInputNumber.className = "block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
    newInputNumber.classList.add('dynamic-input');
    newInputNumber.name = `produits[${inputValue}]`;
    newInputNumber.placeholder = `10`;
    newInputNumber.required = true;

  const labelIpnut = document.createElement('label');
    labelIpnut.className="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300";
    labelIpnut.textContent = selectedOption;
 
  newInputContainer.appendChild(labelIpnut);
  form.appendChild(newInputContainer);
  form.appendChild(newInputNumber);

  users = removeObjectByIndex(users, indeX);
  // console.log(indeX,'supprimer Object');
}

document.addEventListener("click", () => {
  hideDropdown();
});

function hideDropdown() {
  let dropdownEl = document.querySelector("#dropdown");
  dropdownEl.classList.add("hidden");
}

function removeObjectByIndex(users, index) {
  return [
    ...users.slice(0, index),
    ...users.slice(index + 1)
  ];
}


</script>