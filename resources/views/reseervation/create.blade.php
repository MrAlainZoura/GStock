@extends('base')
@section('title', "Ventes ")

@section('header')
  @include('composant.hearder', ['user_email'=>Auth::user()->email, 'user_name'=>Auth::user()->name])
@endsection

@section('main')  

    @if(session('success'))
    <div class="alert-success">
    @include('composant.alert_suc', ['message'=>session('success')])
    </div>
    @endif
    <div class="alert-success">
      @include('composant.alert_suc', ['message'=>"Vous effectuer cette vente pour le compte de ".$depot->libele])
    </div>
      <div class="alert-echec-submit hidden" id="submitErreur">
          @include('composant.alert_echec', ['message'=>"Actualiser la page et réessayer si c'était par erreur que vous avez cliqué sur Ajouter"])
      </div>
    
    @if(session('echec') )
      <div class="alert-echec">
          @include('composant.alert_echec', ['message'=>session('echec')])
      </div>
    @endif
    
    <div id="alert-additional-content-2" class="hidden p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 dark:border-red-800" role="alert">
      <div class="flex items-center">
        <svg class="shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <h3 class="text-lg font-medium">Erreur, vente impossible</h3>
      </div>
      <div class="mt-2 mb-4 text-sm">
          Veuillez au moins choisir un produit pour effectuer cette vente, sinon elle n'aura pas de sens!
      </div>
      <div class="flex item-center justify-center">
        <button type="button" onclick="alertErreurProduitSend('hide')" class="text-red-800 bg-transparent border border-red-800 hover:bg-red-900 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:hover:bg-red-600 dark:border-red-600 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800">
          Reprennez
        </button>
      </div>
    </div>

    <div class="p-10">

        <form id="myFormVente" action="{{route('venteStore',$depot->libele)}}" method="post" enctype="multipart/form-data">
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
                                <label for="link-checkbox" id="labeleAncien" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Voir nécessaire </label>
                            </div>
                        </div>
                        <hr class="h-px  bg-black border-0 dark:bg-gray-700">
                    </div>
                        <div class="w-full">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                            <input type="text" name="nom_client" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="nom">
                        </div>
                        <div class="w-full" id="divPrenom">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prenom</label>
                            <input type="text" name="prenom" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Christ" >
                        </div>
                        <div id="divGenre">
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
                        <div class="w-full" id="divAdress">
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
                            <div class="w-full sm:col-span-2">
                                <label for="deviseSelect" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choisir une Monnaie</label>
                                <select id="deviseSelect" name="monnaie" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    
                                </select>
                            </div>
                            <div class="w-full sm:col-span-2" >
                                <input type="number" required min="1" name="updateDevise" id="updateDevise" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="1000" >
                            </div>
                            <div class="flex items-center">
                                <input id="venteFC" type="checkbox" value="false" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="venteFC" id="venteFC" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Vendre en CDF</label>
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
                            <div>
                            <label for="paie" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mode de Paiement</label>
                            <select id="paie" required name="tranche" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option selected value="">Choisir le mode de paiement </option>
                                <option value=false>Paiement cash</option>
                                <option value=true>Paiement par tranche</option>
                            </select>
                          
                            <div class="w-full hidden" id="inputTranche">
                                <label for="tranche" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Première tranche</label>
                                <input type="number" min="1" name="trancheP" id="tranche" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="1000" >
                            </div>
                            
                        </div>
                          </div> 
                    </div>
                    

              </div>
              
              <div class="flex justify-center m-5">
                <button type="submit"  class="btn-primary w-1/2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Ajouter</button>
            </div>
          </form>
    </div>
    
    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=>$depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    
let users = @json($produit);
const devise = @json($depot->devise);

let prodListTab=[];
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

//script tranche 
const tranche =()=> {document.getElementById('paie').addEventListener('change', function() {
        var select = document.getElementById('paie');
        var tranche = document.getElementById('inputTranche');
        var inputTrans = document.getElementById('tranche');
        const valeur = select.value;
        if(valeur =='true'){
          select.value = true;
          tranche.classList.remove("hidden");
          inputTrans.setAttribute("required", true)
        }else{
          select.value = false;
          tranche.classList.add('hidden');
          inputTrans.removeAttribute("required")
        }
        // console.log(select.value, true)
        ;})
}
document.addEventListener("DOMContentLoaded", () => {
  renderOptions(users);
  today();
  ancien();
  submitMyForm();
  tranche();
  alertErreurProduitSend('hide');
  deviseRender();
});

function renderOptions(options) {
  let dropdownEl = document.querySelector("#dropdown");

  let newHtml = ``;
  let compteur=0;


  options.forEach((user,indice) => {
    newHtml += `<div
      onclick="updateDivProduit(${user.produit.id})"
      class="px-5 py-3 border-b border-gray-200 text-stone-600 cursor-pointer hover:bg-slate-100 transition-colors"
    >
      ${user.produit.marque.libele} ${user.produit.libele}
    </div>`;

      if(compteur < 3){
        dropdownEl.innerHTML = newHtml;
        compteur++;
      }
    });
}

function updateDivProduit(index){
  const produit_id = index;
   let original = users.find(o => o.produit.id === produit_id);
   let indexOrigin = users.findIndex(o => o.produit.id === produit_id);
  // console.log('produit id ',indexOrigin)
  prodListTab = [...prodListTab, original];
  users = removeObjectByIndex(users, parseInt(indexOrigin));
  const dernierProduit = prodListTab.length-1;
  const div = prodListTab[dernierProduit]
  selectOption(`${div.produit.marque.libele} ${div.produit.libele}`, `${div.produit.id}`, `${dernierProduit}`)
}

function removeDivProduit(index, idDiv) {
  users = [...users, prodListTab[index]];                    // Ajoute l'élément retiré à users
  prodListTab = removeObjectByIndex(prodListTab, index);     // Supprime l'élément du tableau
  const deleteDivLabel = document.getElementById(`divLabel${idDiv}`);          // Cherche le div dans le DOM
  const deleteDivPrix = document.getElementById(`divPrix${idDiv}`);          // Cherche le div dans le DOM
  if (deleteDivLabel) {
    deleteDivPrix.remove()
    deleteDivLabel.remove();                                      // Supprime du DOM s'il existe
  }
}
function selectOption(selectedOption, inputValue, index) {
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
   newInputQte.setAttribute("oninput", `setTotal('${newInputQte.id}','${newInputPrx.id}','${newInputTotal.id}')`);

  const divQtPrix = document.createElement('div');
        divQtPrix.className="flex gap-2";
        divQtPrix.id=`divPrix${inputValue}`;

  const divLabel = document.createElement('div');
        divLabel.className="flex";
        divLabel.id=`divLabel${inputValue}`;
  const svgDelete = `
                    <svg width="15px" height="15px" viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                      <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                      <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                      <g id="SVGRepo_iconCarrier">
                      <path d="M960 160h-291.2a160 160 0 0 0-313.6 0H64a32 32 0 0 0 0 64h896a32 32 0 0 0 0-64zM512 96a96 96 0 0 1 90.24 64h-180.48A96 96 0 0 1 512 96zM844.16 290.56a32 32 0 0 0-34.88 6.72A32 32 0 0 0 800 320a32 32 0 1 0 64 0 33.6 33.6 0 0 0-9.28-22.72 32 32 0 0 0-10.56-6.72zM832 416a32 32 0 0 0-32 32v96a32 32 0 0 0 64 0v-96a32 32 0 0 0-32-32zM832 640a32 32 0 0 0-32 32v224a32 32 0 0 1-32 32H256a32 32 0 0 1-32-32V320a32 32 0 0 0-64 0v576a96 96 0 0 0 96 96h512a96 96 0 0 0 96-96v-224a32 32 0 0 0-32-32z" fill="#231815"/>
                      <path d="M384 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM544 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0zM704 768V352a32 32 0 0 0-64 0v416a32 32 0 0 0 64 0z" fill="#231815"/>
                      </g>
                    </svg>
                    `;
  const deleteBtn = document.createElement('button');
        deleteBtn.className = "mr-1 text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm text-center  dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900";
        deleteBtn.innerHTML = svgDelete;
        deleteBtn.type = 'button';
        // deleteBtn.id = `${inputValue}`;
        deleteBtn.setAttribute('onclick',`removeDivProduit('${index}', '${inputValue}')`)

  const labelIpnut = document.createElement('label');
    labelIpnut.className="bloc mb-2 text-sm font-medium text-gray-900 dark:text-white";
    labelIpnut.textContent = selectedOption;
 
  divLabel.appendChild(deleteBtn);
  divLabel.appendChild(labelIpnut);
  divQtPrix.appendChild(newInputQte);
  divQtPrix.appendChild(newInputPrx);
  divQtPrix.appendChild(newInputTotal);
  form.appendChild(divLabel);
  form.appendChild(divQtPrix);
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
  const devise = document.getElementById('deviseSelect').value.trim();
  const getDevise =  devise.substring(devise.indexOf('-') + 1);
  
  const paieFc = document.getElementById('venteFC');
     const valeur = ()=>  {paieFc.addEventListener('change', function (){
        this.value = this.checked ? true : false;
      });
      return paieFc.checked;
    }
  const monnaieTransaction = (valeur())?'cdf':getDevise;
  const calculNet = (net)=>{
    let somme = 0;
    const inputs = document.querySelectorAll('.putainDesabled');
    const updateDevise = document.getElementById('updateDevise').value.trim();

    inputs.forEach((input)=>{
      const total = input.value.split(" ");
      let tabTobal = total[0];
      if(tabTobal.length > 4){
        tabTobal = tabTobal.replace(/\./g, '');
      }
      if(parseInt(tabTobal)){
        somme = parseInt(somme) + parseInt(tabTobal);
      }
       if (document.getElementById('tranche')) {
        const maxTranchePaie = document.getElementById('tranche');
        maxTranchePaie.max = somme; //Ajouter du maximum au champ de premiere tranche
      }
    });
    //recupere paiement en franc
    if(valeur()){
      const sommeFormater = somme.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      let sommeDevise =(somme / updateDevise).toFixed(2);
      sommeDevise = sommeDevise.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      net.textContent = `Montant net à payer ${sommeDevise} ${getDevise} / ${somme} cdf`;
    }else{
      const sommeFormater = somme.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
      let sommeFc =somme * updateDevise;
      sommeFc =sommeFc.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      net.textContent = `Montant net à payer ${sommeFormater} ${getDevise} / ${sommeFc} cdf`;
    }
  }
  const idStr = (str)=>{
    const getId = str.match(/\D*(\d+)/);
    if (getId) {
        return parseInt(getId[1]);
    }
  }

  const renderTt = (quantity,prix,text, idInter,net, monnaieTransaction)=>{
    let total = quantity * prix;
    total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    text.value = `${total} ${monnaieTransaction}`;
    const id = idStr(idInter);
    text.name= `produits[${id}][${inputQt.value}]`;
    // console.log(monnaieTransaction);
    calculNet(net);
  }

  inputPrx.addEventListener('input', (event)=>{
   if( inputQt.value !=null){
    renderTt(inputQt.value,inputPrx.value,showTt, inputQt.id, netPayer, monnaieTransaction)
   }
  });
  inputQt.addEventListener('input', (event)=>{
   if( inputPrx.value !=null){
    renderTt(inputQt.value,inputPrx.value,showTt, inputQt.id, netPayer, monnaieTransaction)
   }
  });
  
};

const ancien = ()=>{
    const ancienClient = document.getElementById('link-checkbox');
    const labelancien = document.getElementById('labeleAncien');

    const tel =document.getElementById('tel');
    const genre = document.getElementById('divGenre')
    const prenom = document.getElementById('divPrenom')
    const adresse = document.getElementById('divAdress')
    ancienClient.addEventListener('change', function() {
      if (this.checked) {
        this.value= true;
        // tel.required =true;
        genre.classList.add("hidden");
        prenom.classList.add("hidden");
        adresse.classList.add("hidden");
        labelancien.textContent="Voir plus"
      } else {
        this.value=false;
        // tel.required=false;
        genre.classList.remove("hidden");
        prenom.classList.remove("hidden");
        adresse.classList.remove("hidden");
        labelancien.textContent="Voir nécessaire"
      }
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
let compteurSubmit = 0;
const submitMyForm = ()=>{

  const myFormVente =document.getElementById('myFormVente');
  myFormVente.addEventListener('submit', (event)=>{
    event.preventDefault();
    const paiementFc = document.getElementById('venteFC');
    let updateDevise = document.getElementById('updateDevise').value.trim();
    const valeur = ()=>  {paiementFc.addEventListener('change', function (){
        this.value = this.checked ? true : false;
      });
      return paiementFc.checked;
    }
    //  const inputs = myFormVente.querySelectorAll('.putainDesabled');
  //  const inputs = [...myFormVente.querySelectorAll('.putainDesabled')];
  const inputs = Array.from(myFormVente.querySelectorAll('.putainDesabled'));

   inputs.forEach((input)=>{
    input.removeAttribute('disabled')
    input.removeAttribute('aria-label')

     const total = input.value.split(" ");
      let totalEntier = total[0];
      if(totalEntier.length > 4){
        totalEntier = totalEntier.replace(/\./g, '');
      }
      if(compteurSubmit == 0){
        if(!valeur()){
          input.value = totalEntier;
        }else{
          input.value = (parseFloat(totalEntier)/updateDevise).toFixed(2);
        }
      }
      // console.log(input.value, paiementFc.value, updateDevise);
      // console.log(totalEntier, updateDevise, input.value, paiementFc, compteurSubmit)
   });
   compteurSubmit++;
   const errreMessage = document.getElementById('submitErreur');
   (compteurSubmit >0)?errreMessage.classList.remove('hidden'):"";
   
   const maxTranchePaie = document.getElementById('tranche');
   if(valeur() && maxTranchePaie && !isNaN(updateDevise) && updateDevise !== 0)
      {
        maxTranchePaie.value=(parseFloat(maxTranchePaie.value)/updateDevise).toFixed(2);
      }
   // console.log(prodListTab.length, inputs, paiementFc.value);
   (prodListTab.length==0) ? alertErreurProduitSend('show') : myFormVente.submit();
    // myFormVente.submit();
  })
};

const deviseRender = () => {
  const seltDevise = document.getElementById('deviseSelect');
  const updateDevise = document.getElementById('updateDevise');

  devise.forEach(dev => {
    const option = document.createElement("option");
    option.value = `${dev.id}-${dev.libele}`;
    option.setAttribute('data-taux', dev.taux);
    option.textContent = `${dev.libele} ${dev.taux}`;
    seltDevise.appendChild(option);
  });

  seltDevise.addEventListener('change', function () {
    const selected = seltDevise.options[seltDevise.selectedIndex];
    const attributTaux = selected.dataset.taux; // ou getAttribute('data-taux')

    // console.log(seltDevise.value, attributTaux);
    updateDevise.value = attributTaux;
  });
  seltDevise.dispatchEvent(new Event('change'));
};
</script>