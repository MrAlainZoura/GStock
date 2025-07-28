@extends('base')
@section('title', "Transfert $depot->libele")

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
    

    <section class="p-10 gap-5 w-full">
         <div id="alert-additional-content-2" class="hidden p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
      <div class="flex items-center">
        <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <h3 class="text-lg font-medium">Erreur, transfert impossible</h3>
      </div>
      <div class="mt-2 mb-4 text-sm">
          Veuillez au moins choisir un produit pour effectuer ce trnasfert, sinon ça n'aura pas de sens !
      </div>
      <div class="flex item-center justify-center">
        <button type="button" onclick="alertErreurProduitSend('hide')" class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800">
          Reprennez
        </button>
      </div>
    </div>

    <section class="bg-white dark:bg-gray-900">
  <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
      <h2 id="titleTransfert" class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Transfert Produit {{$depot->libele}} vers...</h2>
      <form  action="{{route('transStore', $depot->libele)}}" id="formTrans" method="post">
        @method('post')
        @csrf
        <input type="hidden" name="depot_id" value="{{$depot->id}}">
          <div class="grid gap-4 grid-cols-1 sm:gap-6">
          <div class="mb-5 sm:col-span-2">
                  <label for="destination" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selectionner la destination</label>
                  <select required name="destination" required id="destination" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option selected value="">Choisir une destinantion</option>
                      @foreach($depotList as $k=>$v)
                      <option value="{{$v->libele}}">{{$v->libele}}</option>
                      @endforeach
                  </select>
              </div>
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
              
              <div class="mb-5 w-full sm:col-span-2 gap-4 grid grid-cols-2 sm:gap-6">
                  <div class="w-full">
                    <label for="produit" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Produit</label>
                  </div>
                  <div class="w-full">
                      <label for="qte" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quantité</label>
                  </div>
              </div>
              <div id="dynamicForm"  class="mb-2 w-full sm:col-span-2 gap-4 grid grid-cols-2 sm:gap-6">
                    
              </div>

             <div class="">
               <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Observation</label>
               <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your thoughts here..."></textarea>
             </div>

          </div>
        <div class="flex justify-center m-5">
            <button type="submit" id="btnSubmit"  class="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Sectionner une destinantion</button>
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
let prodList = [];
// console.log(users)
function onkeyUp(e) {
  let keyword = e.target.value;
  let dropdownEl = document.querySelector("#dropdown");
  if (document.activeElement===e.target) {
    dropdownEl.classList.remove("hidden"); 
  }

  let filteredusers = users.filter((c) => {
    if (c.produit.libele.toLowerCase().includes(keyword.toLowerCase())) {
        return true; 
    }
    if (c.produit.description.toLowerCase().includes(keyword.toLowerCase())) {
        return true; 
    }
    if (c.produit.marque.libele.toLowerCase().includes(keyword.toLowerCase())) {
        return true; 
    }
    if (c.produit.marque.categorie.libele.toLowerCase().includes(keyword.toLowerCase())) {
        return true; 
    }
    return false;
});
  renderOptions(filteredusers);
}
document.addEventListener("DOMContentLoaded", () => {
  renderOptions(users);
  changeTextSubmit();
  submitMyForm();
  alertErreurProduitSend('hide');
});

function renderOptions(options) {
  let dropdownEl = document.querySelector("#dropdown");

  let newHtml = ``;
  let compteur =0;

  options.forEach((user,indice) => {
    newHtml += `<div
      onclick="selectOption('${user.produit.marque.libele} ${user.produit.libele} ${user.produit.etat}', '${user.produit.id}')"
      class="px-5 py-3 border-b border-gray-200 text-stone-600 cursor-pointer hover:bg-slate-100 transition-colors"
    >
      ${user.produit.marque.libele} ${user.produit.libele} ${user.produit.etat}
    </div>`;

    if(compteur < 3){
         dropdownEl.innerHTML = newHtml;
         compteur++;
       }
  });
  // dropdownEl.innerHTML = newHtml;
}

function selectOption(selectedOption, inputValue) {
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
    newInputNumber.className = "block text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
    newInputNumber.classList.add('dynamic-input');
    newInputNumber.name = `produits[${inputValue}]`;
    newInputNumber.placeholder = `10`;
    newInputNumber.required = true;

  const labelIpnut = document.createElement('label');
    labelIpnut.className="w-full p-2 flex text-sm font-medium text-gray-900 dark:text-gray-300";
    labelIpnut.textContent = selectedOption;
 
  newInputContainer.appendChild(labelIpnut);
  form.appendChild(newInputContainer);
  form.appendChild(newInputNumber);
  const produit_id = parseInt(inputValue);

  let original = users.find(o => o.produit.id === produit_id);
  let indexOrigin = users.findIndex(o => o.produit.id === produit_id);
  prodList = [...prodList, original];
  users = removeObjectByIndex(users, indexOrigin);
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
 const changeTextSubmit = ()=>{
  let depotName = @json(session('depot'));
  const btnSubmit =document.getElementById('btnSubmit');
  const selectdDestination = document.getElementById('destination');
  const title = document.getElementById('titleTransfert');

  selectdDestination.addEventListener('change', function() {
      const selectedValue = this.value;
      btnSubmit.textContent = "Transferer produit à " +selectedValue;
      title.textContent = `Transfert Produit ${depotName} vers ${selectedValue}`
  });
 }

 const alertErreurProduitSend = (action)=>{
  const divErreur = document.getElementById('alert-additional-content-2')
  if(action=='hide'){
      divErreur.classList.add('hidden');
    }
    if(action=="show"){
      divErreur.classList.remove("hidden");
    }
}
 const submitMyForm = ()=>{
  const myFormTrans =document.getElementById('formTrans');
  myFormTrans.addEventListener('submit', (event)=>{
    event.preventDefault();
   const inputs = myFormTrans.querySelectorAll('.putainDesabled');

  (prodList.length==0)?alertErreurProduitSend('show'):myFormTrans.submit();
  })
};
</script>