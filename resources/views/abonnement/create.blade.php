@extends('base')
@section('title', "Liste de vente")

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

        
        <div class=" p-10 w-full">

            <section class="bg-white py-8 antialiased dark:bg-gray-900 md:py-16">
                <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                    <div class="mx-auto max-w-5xl">
                        <div class="flex justify-between">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Souscription d'abonnement</h2>
                            
                            <button type="button" onclick="cancel('{{ route('abonnement.list',Auth::user()->id*13) }}')" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <div class="mt-6 sm:mt-8 lg:flex lg:items-start lg:gap-12">
                            <form action="{{ route('souscr.store') }}" method="post" id="formulaire" class="w-full rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6 lg:max-w-xl lg:p-8">
                                @method('post')
                                @csrf
                                <div class="mb-6 grid grid-cols-2 gap-4">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="full_name" class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"> Type d'abonnement </label>
                                        <select id="selectAbonnement" name="abonnement_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <!-- <option selected value="">Choisir votre abonnement</option> -->
                                            @foreach ($abonnement as $ab)
                                                <option value="{{ $ab->id }}"> {{ $ab->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="debutDate" class="mb-2 flex text-sm font-medium text-gray-900 dark:text-white">
                                            Entrée en vigueur
                                            <div class="pointer-events-none  inset-y-0 start-0 flex items-center ps-3.5">
                                                <svg class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                    fill-rule="evenodd"
                                                    d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                                    clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                        </label>
                                        <div class="relative">
                                            <input datepicker datepicker-format="dd/mm/yyyy" id="debutDate" type="text" name="debut" maxlength="10" class="block p-2.5 w-full rounded-lg border border-gray-300 bg-gray-50  text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" placeholder="25/12/2025" required />
                                        </div>
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="dureemois" class="mb-2 flex items-center gap-1 text-sm font-medium text-gray-900 dark:text-white">
                                            Durée (mois)
                                            <div class="text-gray-400 cursor-pointer hover:text-gray-900 dark:text-gray-500 dark:hover:text-white">
                                            <svg class="h-4 w-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm9.408-5.5a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM10 10a1 1 0 1 0 0 2h1v3h-1a1 1 0 1 0 0 2h4a1 1 0 1 0 0-2h-1v-4a1 1 0 0 0-1-1h-2Z" clip-rule="evenodd" />
                                            </svg>
                                            </div>
                                            <!-- <div id="cvv-desc" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300 dark:bg-gray-700">
                                            The last 3 digits on back of card
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                            </div> -->
                                        </label>
                                        <input type="number"  min="1" name="duree"  id="dureemois" aria-describedby="helper-text-explanation" class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder:text-gray-400 dark:focus:border-primary-500 dark:focus:ring-primary-500" placeholder="•••" required />
                                    </div>
                                </div>
                                <input type="text" class="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <button type="submit" class="flex w-full items-center justify-center rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4  focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Souscrire maintenant</button>
                            </form>
                            <!-- information transaction -->
                            <div class="mt-6 grow sm:mt-8 lg:mt-0">
                                <div class="space-y-4 rounded-lg border border-gray-100 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="space-y-2">
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Prix original/mois</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white" id="orginalPrice">$0</dd>
                                    </dl>

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Nombre point de vente</dt>
                                        <dd class="text-base font-medium text-green-500" id="maxDepot">0</dd>
                                    </dl>

                                    <!-- <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Nombre de mois</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white" id="maxMonth">1</dd>
                                    </dl> -->

                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Debut</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white" id="debut">DD/MM/AAAA</dd>
                                    </dl>
                                    <dl class="flex items-center justify-between gap-4">
                                        <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Expire</dt>
                                        <dd class="text-base font-medium text-gray-900 dark:text-white" id="expire">DD/MM/AAAA</dd>
                                    </dl>
                                    </div>

                                    <dl class="flex items-center justify-between gap-4 border-t border-gray-200 pt-2 dark:border-gray-700">
                                        <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                                        <dd class="text-base font-bold text-gray-900 dark:text-white" id="totalG">$ 0</dd>
                                    </dl>
                                </div>

                                <div class="mt-6 flex items-center justify-center gap-8">
                                    <img class="h-8 w-auto dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/paypal.svg" alt="" />
                                    <img class="hidden h-8 w-auto dark:flex" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/paypal-dark.svg" alt="" />
                                    <img class="h-8 w-auto dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/visa.svg" alt="" />
                                    <img class="hidden h-8 w-auto dark:flex" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/visa-dark.svg" alt="" />
                                    <img class="h-8 w-auto dark:hidden" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/mastercard.svg" alt="" />
                                    <img class="hidden h-8 w-auto dark:flex" src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/brand-logos/mastercard-dark.svg" alt="" />
                                </div>
                            </div>
                        </div>

                        <!-- <p class="mt-6 text-center text-gray-500 dark:text-gray-400 sm:mt-8 lg:text-left">
                            Payment processed by <a href="#" title="" class="font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">Paddle</a> for <a href="#" title="" class="font-medium text-primary-700 underline hover:no-underline dark:text-primary-500">Flowbite LLC</a>
                            - United States Of America
                        </p> -->
                    </div>
                </div>
            </section>

        </div>
   
    @endsection

@include('composant.sidebarAdmin',["user_id"=>auth::user()->id])

@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const abList = @json($abonnement);
        const select = document.getElementById('selectAbonnement');
        const orginalPrice = document.getElementById('orginalPrice');
        // const maxMonth = document.getElementById('maxMonth');
        const maxDepot = document.getElementById('maxDepot');
        const dureemois = document.getElementById('dureemois');
        const debutDate = document.getElementById('debutDate');
        const debut = document.getElementById('debut');
        const expire = document.getElementById('expire');
        const totalG = document.getElementById('totalG');

        const getMois = () => dureemois.value === '' ? 1 : parseInt(dureemois.value);
        const ajouterMois = (dateInitiale, nombreDeMois) => {
            const [jour, mois, annee] = dateInitiale.split("/").map(Number);
            const date = new Date(annee, mois - 1, jour);
            date.setMonth(date.getMonth() + nombreDeMois);
            return date;
        };
        const calculerMontantAvecReduction = (montant, dureeAbonnement, reductionParTrimestre = 2, reductionMax = 20 ) => {
            if (dureeAbonnement < 3) {
                return {
                reduction: 0,
                montantFinal: Math.round(montant)
                };
            }

            const dureeValide = dureeAbonnement - (dureeAbonnement % 3);

            const trimestres = Math.floor(dureeValide / 3);
            const reductionTotale = Math.min(trimestres * reductionParTrimestre, reductionMax);
            const montantFinal = montant * (1 - reductionTotale / 100);

            return {
                reduction: reductionTotale,
                montantFinal: Math.round(montantFinal)
            };
        };
         

        const updateAbonnement = () => {
            const id = parseInt(select.value);
            const abonnement = abList.find(item => item.id === id);
            const mois = getMois();

            dureemois.value = mois;
            orginalPrice.textContent = `$ ${abonnement.prix}`;
            maxDepot.textContent = `${abonnement.max}`;
            calculTotal(abonnement.prix, mois, totalG);
            updateInputLabel(debutDate, debut, expire);
        };

        const calculTotal = (prix, mois, label) => {
            const total = parseInt(prix) * parseInt(mois);
            const { reduction: red, montantFinal: final } = calculerMontantAvecReduction(prix, mois);
            // label.textContent = `$ ${final} `+ (red > 1)?`red(${red}%)`:"";
            orginalPrice.textContent =`$ ${final}`;
            label.textContent = `$ ${final*mois} ${red > 1 ? `red(${red}%)` : ''}`;
        };

        const updateInputLabel = (input, label, expire) => {
            input.value = input.value === '' ? new Date().toLocaleDateString('fr-FR') : input.value;
            label.textContent = input.value;
            expire.textContent = ajouterMois(input.value,getMois()).toLocaleDateString()
        };

    
        updateAbonnement();

        select.addEventListener('change', updateAbonnement);
        dureemois.addEventListener('input', updateAbonnement);
        debutDate.addEventListener('input', () => updateInputLabel(debutDate, debut));
        
    });

    function cancel (url){
        if (!url || typeof url !== 'string') {
            console.log('URL invalide.');
            return;
        }
        const back = window.open(url, "_self");
    }

</script>