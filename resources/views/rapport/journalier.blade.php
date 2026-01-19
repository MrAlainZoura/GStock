@extends('base')
@section('title', "Rapport journalier ". session('depot'))

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
    <!-- <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl dark:text-white">Team management</h2> -->

    <section class="p-10 gap-5 w-full">
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-2xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Rapport journalier  {{session('depot')}} <span id="today"></span> </h1>
        </div>
        <div class="flex justify-end m-5 gap-4">
            <div class="ml-4 shrink-0">
                <button  data-modal-target="crud-modal" data-modal-toggle="crud-modal" class="text-body text-blue-700 cursor-pointer bg-neutral-secondary-medium box-border border border-default-medium rounded-lg hover:bg-neutral-tertiary-medium hover:text-heading focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Plus d'options</button>
            </div>
            <a href="{{route('rapportDownload',['depot'=>$depot->id*12, 'periode'=>'today'])}}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Télécharger PDF
                <img src="{{asset('svg/pdf.svg')}}" class="w-6 rounded" alt="">
            </a>
        </div>
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">1. VENTE </h1>
        </div>
        
        @include('composant.rapportTable',['data'=> $venteJour,"period"=>"Recette du jour", "compassassion"=>$compassassion])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">2. APPROVISIONNEMENT </h1>
        </div>
        @include('composant.rapportTableAppro',['data'=> $approJour])
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. TRANSFERT </h1>
        </div>
        @include('composant.rapportTableTrans',['data'=> $transJour])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. RESUME STOCK </h1>
        </div>
            @include('composant.rapportTableResume',['data'=> $prodArrayResume])
    </section>

    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=> $depot->id])
    @endsection

       <!-- modal pdf telechargement -->
 <div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Télécharger le rapport d'un jour particulier
                </h3>
                <button type="button" class="text-body bg-transparent hover:bg-neutral-tertiary hover:text-heading rounded-base text-sm w-9 h-9 ms-auto inline-flex justify-center items-center" data-modal-hide="crud-modal">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <div class="space-y-4">
                  
                   <div class="grid gap-4 grid-cols-1 sm:grid-cols-3">
                     <div>
                         <label for="annee" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Année</label>
                         <input type="number" min="2025" id="annee" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="2025" required />
                     </div>
                     <div>
                         <label for="mois" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mois</label>
                         <input type="number" min="1" max="12"  id="mois" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                     </div>
                     <div>
                         <label for="jour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jour</label>
                         <input type="number" min="1" max="31"  id="jour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                     </div>
                   </div>
                    
                    <div class="flex items-center space-x-4 border-t border-default pt-4 md:pt-6">
                      <button type="button" id="btnTelcharger" onclick="dwload()" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Télécharger maintenant</button>
                  </div>
                </div>
               
            </div>
        </div>
    </div>
</div> 

@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        today();
        dwload();
    });
    const today = ()=>{
        const dateTimeElement = document.getElementById('today');

        function updateDateTime() {
            const currentDateTime = new Date();

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

            const capitalizedDateTime = formattedDateTime.replace(/\b\w/g, (match) => match.toUpperCase());
            dateTimeElement.textContent = capitalizedDateTime;
        }

        setInterval(updateDateTime, 1000);
    }
        
    const link = (url)=>{
        if (!url || typeof url !== 'string') {
            console.log('URL invalide.');
            return;
        }
        const openLink = window.open(url, '_self');
    }

    const dwload =()=>{
        const valAnnee = window.document.getElementById('annee');
        const valMois = window.document.getElementById('mois');
        const valJour = window.document.getElementById('jour');
        const parameters =`${valAnnee.value}-${valMois.value}-${valJour.value}`;
        let url = @json(route('rapportDownload',["depot"=>$depot->id*12, "periode"=>"today"]));
            url =`${url}/${parameters}`;
        console.log("les valeurs : ",valAnnee.value, valMois.value, valJour.value)
        isValidDate(parseInt (valAnnee.value), parseInt(valMois.value), parseInt(valJour.value)) 
            ? link( url)
            : null ;
    }

    const isValidDate = (year, month, day) => {
        const d = new Date(year, month - 1, day);
        return (
            year >= 2025 &&
            d.getFullYear() === year &&
            d.getMonth() === month - 1 &&
            d.getDate() === day
        );
    };


</script>