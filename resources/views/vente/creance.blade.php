@extends('base')
@section('title', "Vente ")

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
        <table id="search-table" class="w-full">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center max-w-sm">
                            Vendeur
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center max-w-sm">
                            Client
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center max-w-sm">
                            Produit
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                   
                    <th>
                        <span class="flex items-center max-w-sm">
                            Tranche
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                        Net
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                        Action
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
                  
                </tr>
            </thead>
            <tbody>
        
                @foreach ($tabSyntese as $key=>$item )
                <tr>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="flex gap-2 sm:bloc">
                            {{$item['vendeur']}}             
                        </div>
                    </td>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="flex gap-2 sm:bloc">
                            {{$item['client']['nom']}} <br> 
                            {{$item['client']['tel']}}  <br>
                            {{$item['client']['date']}}             
                        </div>
                    </td>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="gap-2 sm:bloc">
                            @foreach($item['prod'] as $c=>$vP)
                            <label for="" class="block">
                                {{$c+1}}. {{$vP}}
                            </label>
                            @endforeach                                         
                        </div>
                    </td>
                    <td class="font-medium text-gray-900  dark:text-white">
                        
                           @foreach($item['tranche'] as $c=>$vT)
                            <label for="" class="block">
                              {{$c+1}}.  {{$vT}}
                            </label>
                            @endforeach
                    </td>
                    <td >
                       {{$item['net']}}
                       
                    </td>
                   
        
                    <td>
                        @if($item['completed']!=true)
                        <a data-modal-target="crud-modal" data-modal-toggle="crud-modal"  key="{{$vT}}" itemNet ="{{$item['net']}}" id="linkPaie" href="{{route('creanceStore', $key*8943)}}" class="relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-teal-300 to-lime-300 group-hover:from-teal-300 group-hover:to-lime-300 dark:text-white dark:hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-lime-200 dark:focus:ring-lime-800">
                            <span class="flex relative p-1 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent">
                                <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                                <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                                <svg fill="#a2a0a0" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="28px" height="28px" viewBox="0 0 209 256" enable-background="new 0 0 209 256" xml:space="preserve">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                <g id="SVGRepo_iconCarrier"> <path d="M120.523,2l-1.877,23.625H96.625v78.681h-2.458c-14.746,0-28.129,8.628-34.214,22.06L2.125,254h78.75l5.07-16.892 C122.588,225.945,132.216,191,132.216,191h51.034v-18.12l10.529,0.837L206.875,8.861L120.523,2z M175.375,166.606 c-7.757,2.097-13.697,8.53-15.077,16.519h-44.767c-2.957,15.124-14.146,28.884-29.224,35.043c-0.487,0.199-0.992,0.293-1.488,0.293 c-1.552,0-3.023-0.924-3.647-2.449c-0.822-2.013,0.143-4.311,2.156-5.134c14.782-6.038,25.106-20.689,25.106-35.628 c0-0.007,0.002-3.938,0.002-3.938h40.032c5.948,0,11.048-4.654,11.154-10.601c0.108-6.056-4.765-10.996-10.797-11.006l-70.906-0.049 c-2.174,0-3.938-1.763-3.938-3.938s1.763-3.938,3.938-3.938h26.58V49.236c7.999-1.632,14.288-7.837,16.1-15.736h38.987 c1.791,7.83,7.972,13.941,15.789,15.668V166.606z M187.86,148.775c-1.608,0.301-3.149,0.796-4.61,1.446V25.625h-53.998 c6.856-1.808,12.395-7.039,14.546-13.876l38.864,3.088c1.166,7.947,6.842,14.528,14.498,16.868L187.86,148.775z M167.696,107.404 c0.028-15.077-12.011-27.764-27.692-27.793c-15.077-0.028-27.766,12.614-27.793,27.692c-0.028,15.077,12.011,27.764,27.692,27.793 C154.98,135.124,167.668,122.482,167.696,107.404z M123.666,109.737l-4.825-0.009l0.01-5.428l4.222,0.008 c0.004-2.413,1.216-5.426,1.823-7.837l6.028,1.821c-0.605,1.205-1.213,3.616-1.22,7.235c-0.005,3.015,1.197,4.827,3.007,4.831 s3.019-1.804,4.836-6.022c2.423-6.027,4.841-9.038,9.666-9.028c4.221,0.611,7.832,4.236,9.031,9.063l4.825,0.009l-0.01,5.428 l-4.222-0.008c-0.005,2.413-1.22,7.235-1.826,9.043l-6.028-1.821c1.21-1.807,2.422-5.423,2.428-8.439 c0.006-3.619-1.196-5.43-3.609-5.434c-1.809-0.004-3.018,1.201-4.835,5.419c-1.819,5.424-4.842,9.641-9.667,9.632 C128.475,118.189,124.861,115.166,123.666,109.737z"/> </g>
                                </svg>
                                Payer
                            </span>
                        </a>
                        @else
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-green-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                            </svg>
                            <a href="{{route('venteShow',56745264509*$key)}}" class="text-blue-600 hover:underline">Soldé</a>
                        </div>
                        @endif
                    </td>
                </tr>
                @endforeach
        
            </tbody>
        </table>
        

        
<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Ajouter un paiement
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <!-- <form class="p-4 md:p-5"> -->
                <form action="#" method="post" id="paieForm"  class="p-4 md:p-5">
                    @csrf
                    @method('post')
                <div class="grid gap-4 mb-4 grid-cols-1">
                    <div class="col-span-2">
                        <label id="netPai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total net </label>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label id="dernierV" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dernier versement </label>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label id="solde" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Solde à payer </label>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="priceNew" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Paiment actuel</label>
                        <input type="number" name="paiment" id="priceNew" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="1000" required="">
                    </div>
                    
                </div>
                <div class="flex justify-center items-center">
                    <button type="submit" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Payer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 

            
       
    </section>

    @include('composant.sidebar',['depot'=> session('depot')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#search-table", {
                searchable: true,
                paging: true,
                perPage: 5,
                perPageSelect: [5, 10, 15, 20, 25, 50,100, 200, 300, 400, 500],
                sortable: true
            });
          
        }
        //MODAL ADD PAY
        const addPayLink = document.querySelectorAll('#linkPaie');
        addPayLink.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const hrefClicked = event.currentTarget.getAttribute('href');
                const paieForm =document.getElementById('paieForm');

                const itemNet = event.currentTarget.getAttribute('itemNet') ;
                const labelNet = document.getElementById('netPai');
                const labelDernierV = document.getElementById('dernierV');
                const labelsolde = document.getElementById('solde');
                const newPaie = document.getElementById('priceNew');

                const paiementBrut = event.currentTarget.getAttribute('key') ;
                const [avance, solde] = paiementBrut.trim().split(" - ").map(Number);
                
                labelNet.textContent= `Total net à payer ${itemNet} Fc`;
                labelDernierV.textContent = `Dernier versement ${parseInt(event.currentTarget.getAttribute('key'))} FC`;
                labelsolde.textContent = `Solde à payer ${solde} Fc`;
                newPaie.max = `${solde}`;
                paieForm.setAttribute('action',hrefClicked);
            });
        });
    });


</script>