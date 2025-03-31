<section class="bg-white py-10 sm:py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0">
                <h2 class="text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl">Vente {{$findVenteDetail->code}} Détails </h2>
                <p class="mt-2 text-lg/8 text-gray-600"> Description de la vente : <br>
                    Vente numero {{$findVenteDetail->code}} effectué par 
                    {{$findVenteDetail->user->name}} {{$findVenteDetail->user->postnom}} {{$findVenteDetail->user->prenom}}
                     pour le compte de {{session('depot')}} en date du 
                     {{$findVenteDetail->created_at}} au client {{$findVenteDetail->client->name}} {{$findVenteDetail->client->prenom}} qui a acheté 
                     {{count($findVenteDetail->venteProduit) }}
                     produit(s) dont 
                     @php
                    $quantite = 0;
                    $netPaye = 0;
                    @endphp
                    @foreach ( $findVenteDetail->venteProduit as $val)
                        @php
                            $quantite +=$val->quantite;
                            $netPaye+=$val->prixT;
                        @endphp
                    @endforeach
                     {{$quantite}} piece(s) au total et le prix net payé @formaMille($netPaye) Fc.
                </p>
                <div class="flex flex-col justify-between sm:flex sm:flex-row sm:flex-1">
                                        
                    <a href="#" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Imprimer
                        <img src="{{asset('svg/print.svg')}}" class="w-8 rounded" alt="">
                    </a>
                    <a href="{{route('facturePDF',$findVenteDetail->id*56745264509)}}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        PDF
                        <img src="{{asset('svg/pdf.svg')}}" class="w-6 rounded" alt="">
                    </a>
                </div>
                <div class="flex flex-col justify-between sm:flex sm:flex-row sm:flex-1">
                    <div class="">
                    <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <div>
                            <span class=""></span>
                            Vendeur : 
                        </div>
                    </h3>
                    <div class="relative mt-8 flex items-center gap-x-4">
                    @if ($findVenteDetail->user->image == null)
                        <img class="w-10 h-10 rounded" src="{{asset('svg/man.svg')}}" alt="Produit">
                    @else
                        <img src="{{asset('storage/users/'.$findVenteDetail->user->image)}}" alt="photo" class="size-14 rounded-sm bg-gray-50">
                    @endif
                        <div class="text-sm/6">
                            <p class="font-semibold text-gray-900">
                            <a href="#">
                                <span class=""></span>
                                {{$findVenteDetail->user->name}} {{$findVenteDetail->user->postnom}} {{$findVenteDetail->user->prenom}}
                            </a>
                            </p>
                            <p class="text-gray-600">{{$findVenteDetail->user->email}}</p>
                        </div>
                    </div>
                </div>
                <div class="">
                    <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <div>
                            Client :
                        </div>
                    </h3>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <div class="text-sm/6">
                            <p class="font-semibold text-red-900">{{$findVenteDetail->client->name}} {{$findVenteDetail->client->prenom}} <br/>{{$findVenteDetail->client->tel}}</p>
                        </div>
                    </div>
                </div>
            </div>
                
            </div>
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none ">
            
                              

                <div class="relative max-w-md">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 rounded-s-lg">
                                    Produit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Qté
                                </th>
                                <th scope="col" class="px-6 py-3 rounded-e-lg">
                                    Prix
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ( $findVenteDetail->venteProduit as $item)  
                            <tr class="bg-white dark:bg-gray-800">
                                <th scope="row" class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{$item->produit->marque->libele}} 
                                    {{$item->produit->libele}}<br>
                                    {{$item->produit->etat}}
                                </th>
                                <td class="px-3 py-2">
                                {{$item->quantite}} pc
                                </td>
                                <td class="px-3 py-2">
                                @formaMille($item->prixT) Fc
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-semibold text-gray-900 dark:text-white">
                                <th scope="row" class="px-4 py-3 text-base">Total</th>
                                <td class="px-3 py-3">
                                  {{$quantite}}
                                   pc
                                </td>
                                <td class="px-3 py-3"> @formaMille($netPaye) Fc</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

               

            </div>
        </div>
    </section>