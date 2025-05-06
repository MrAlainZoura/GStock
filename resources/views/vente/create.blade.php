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

        <form id="myFormVente" action="{{route('venteStore',session('depot'))}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
            <input type="hidden" name="depot_id" value="{{$depot->id*98123}}">
              <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div>
                    <div class="sm:col-span-2 mb-2">
                        <div class="text-lg mb-2 font-medium text-gray-900 dark:text-white">
                            Informations sur le <span class="italic">Client</span>
                            <div class="flex items-center">
                                <input id="link-checkbox" type="checkbox" value="false" name="ancien" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="link-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Ancien </label>
                            </div>
                        </div>
                        <hr class="h-px  bg-black border-0 dark:bg-gray-700">
                    </div>
                        <div class="w-full">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                            <input type="text" name="nom_client" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="nom" required="">
                        </div>
                        <div class="w-full">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prenom</label>
                            <input type="text" name="prenom" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Christ" >
                        </div>
                        <div>
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                            <select id="category" name="genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option selected value="">Choisir genre client</option>
                                <option value="F">F</option>
                                <option value="M">M</option>
                            </select>
                        </div>
                       
                        <div class="w-full">
                            <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Téléphone</label>
                            <input type="text" id="tel" name="contact_client" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="+243 ..." >
                        </div>
                        <div class="w-full">
                            <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adresse</label>
                            <input type="text" name="adresse"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Kin, Lemba, Salongo">
                        </div>
                    </div>
                    <div>
                        <div class="sm:col-span-2 mb-2">
                            <div class="text-lg mb-2 font-medium text-gray-900 dark:text-white">
                                Facture <span class="italic">Prodiut en vente</span>
                                <div class="flex items-center">
                                    <label for="link-checkbox" id="today" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Date du jour</label>
                                </div>
                            </div>
                            <hr class="h-px  bg-black border-0 dark:bg-gray-700">
                        </div>
                        <div class="grid gap-4 grid-cols-1 sm:gap-6">
                            <div class="w-full sm:col-span-2">
                                <label for="lieu" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lieu de vente</label>
                                <select id="lieu" name="lieu_de_vente" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected value="Shop">Au shop</option>
                                    <option value="Livraison">Livraison</option>
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
                        </div>
                        <div id="dynamicForm"  class="mb-5 mt-5 w-full sm:col-span-2 gap-4 grid md:grid-cols-2 sm:gap-4 sm:grid-cols-1">
                           
                        </div>
                        <div class="sm:col-span-2">
                              <div class="flex items-center p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
                              <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                              </svg>
                              <span class="sr-only">Info</span>
                                <span id="netPayer" class="font-medium">Montant net à payer </span>
                            </div>
                          </div> 
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

<script>
    
let users = @json($produit);
// console.log(users)
function onkeyUp(e) {
  let keyword = e.target.value;
  let dropdownEl = document.querySelector("#dropdown");
  dropdownEl.classList.remove("hidden");

  let filteredusers = users.filter((c) =>
    c.produit.description.toLowerCase().includes(keyword.toLowerCase())
  );

  renderOptions(filteredusers);
}

document.addEventListener("DOMContentLoaded", () => {
  renderOptions(users);
  today();
  ancien();
  submitMyForm();
});

function renderOptions(options) {
  let dropdownEl = document.querySelector("#dropdown");

  let newHtml = ``;

  options.forEach((user,indice) => {
    newHtml += `<div
      onclick="selectOption('${user.produit.marque.libele} ${user.produit.libele}', '${user.produit.id}', '${indice}')"
      class="px-5 py-3 border-b border-gray-200 text-stone-600 cursor-pointer hover:bg-slate-100 transition-colors"
    >
      ${user.produit.marque.libele} ${user.produit.libele}
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

  const newInputQte = document.createElement('input');
    newInputQte.type = 'number';
    newInputQte.id = `inputQte${inputValue}`;
    newInputQte.className = "block w-full text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
    newInputQte.classList.add('dynamic-input');
    newInputQte.placeholder = `10`;
    newInputQte.min = `1`;
    newInputQte.title = `Quantité`;
    newInputQte.required = true;

  const newInputTotal = document.createElement('input');
    newInputTotal.type = 'text';
    newInputTotal.className = "putainDesabled block w-full text-gray-900 border border-red-900 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
    newInputTotal.classList.add('dynamic-input');
    newInputTotal.placeholder = `Total`;
    newInputTotal.setAttribute("aria-label", "disabled input");
    newInputTotal.id = `showT${inputValue}`;
    newInputTotal.required = true;
    newInputTotal.disabled = true;

  const newInputPrx = document.createElement('input');
    newInputPrx.type = 'number';
    newInputPrx.id = `inputPx${inputValue}`;
    newInputPrx.className = "block w-full text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
    newInputPrx.classList.add('dynamic-input');
    newInputPrx.placeholder = `10`;
    newInputPrx.min = `1`;
    newInputPrx.title = `Prix unitaire`;
    newInputPrx.placeholder = `Prix unitaire`;
    newInputPrx.setAttribute("oninput", `setTotal('${newInputQte.id}','${newInputPrx.id}','${newInputTotal.id}')`);
    newInputPrx.required = true;

newInputTotal.name = `produits[${inputValue}][${newInputQte.value}]`;

  const divQtPrix = document.createElement('div');
        divQtPrix.className="flex gap-2";

  const labelIpnut = document.createElement('label');
    labelIpnut.className="block mb-2 text-sm font-medium text-gray-900 dark:text-white";
    labelIpnut.textContent = selectedOption;
 
  divQtPrix.appendChild(newInputQte);
  divQtPrix.appendChild(newInputPrx);
  divQtPrix.appendChild(newInputTotal);
  form.appendChild(labelIpnut);
  form.appendChild(divQtPrix);


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

const today = ()=>{
    const dateTimeElement = document.getElementById('today');

    // Fonction pour mettre à jour la date et l'heure
    function updateDateTime() {
    // Récupérer la date et l'heure actuelles
    const currentDateTime = new Date();

    // Formater la date et l'heure
    const options = { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };

        const formattedDateTime = new Intl.DateTimeFormat('fr-FR', options).format(currentDateTime);

        // Mettre la première lettre de chaque mot en majuscule
        const capitalizedDateTime = formattedDateTime.replace(/\b\w/g, (match) => match.toUpperCase());
        dateTimeElement.textContent = capitalizedDateTime;
    }

    setInterval(updateDateTime, 1000);
}


const setTotal = (inputQte, inputPx, showT) => {
  const inputQt = document.getElementById(inputQte);
  const inputPrx = document.getElementById(inputPx);
  const showTt = document.getElementById(showT);
  const netPayer =document.getElementById('netPayer');

  const calculNet = (net)=>{
    let somme = 0;
    const inputs = document.querySelectorAll('.putainDesabled');
    inputs.forEach((input)=>{
      const total = input.value.split(" ");
      let tabTobal = total[0];
      if(tabTobal.length > 4){
        tabTobal = tabTobal.replace(/\./g, '');
      }
      if(parseInt(tabTobal)){
        somme = parseInt(somme) + parseInt(tabTobal);
      }
      const sommeFormater = somme.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      net.textContent = `Montant net à payer ${sommeFormater} Fc`;
    });
  }
  const idStr = (str)=>{
    const getId = str.match(/\D*(\d+)/);
    if (getId) {
        return parseInt(getId[1]);
    }
  }

  const renderTt = (quantity,prix,text, idInter,net)=>{
    let total = quantity * prix;
    total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    text.value = `${total} Fc`;
    const id = idStr(idInter);
    text.name= `produits[${id}][${inputQt.value}]`;
    calculNet(net);
  }

  inputPrx.addEventListener('input', (event)=>{
   if( inputQt.value !=null){
    renderTt(inputQt.value,inputPrx.value,showTt, inputQt.id, netPayer)
   }
  });
  inputQt.addEventListener('input', (event)=>{
   if( inputPrx.value !=null){
    renderTt(inputQt.value,inputPrx.value,showTt, inputQt.id, netPayer)
   }
  });
  
};

const ancien = ()=>{
    const ancienClient = document.getElementById('link-checkbox');
    const tel =document.getElementById('tel');
    ancienClient.addEventListener('change', function() {
      if (this.checked) {
        this.value= true;
        tel.required =true;
      } else {
        this.value=false;
        tel.required=false;
      }
    });
}

const submitMyForm = ()=>{
  const myFormVente =document.getElementById('myFormVente');
  myFormVente.addEventListener('submit', (event)=>{
    event.preventDefault();
   const inputs = myFormVente.querySelectorAll('.putainDesabled');
   inputs.forEach((input)=>{
    input.removeAttribute('disabled')
    input.removeAttribute('aria-label')

    const total = input.value.split(" ");
      let totalEntier = total[0];
      if(totalEntier.length > 4){
        totalEntier = totalEntier.replace(/\./g, '');
      }
      input.value = totalEntier;
   })
  myFormVente.submit();
  })
}
</script>