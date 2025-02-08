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
                    <div class="">
                    <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <div>
                            <span class=""></span>
                            Vendeur :
                        </div>
                    </h3>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        <img src="{{asset('storage/users/'.$findVenteDetail->user->image)}}" alt="photo" class="size-14 rounded-sm bg-gray-50">
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
            <div class="mx-auto mt-10 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 border-t border-gray-200 pt-10 sm:mt-16 sm:pt-16 lg:mx-0 lg:max-w-none lg:grid-cols-3">
            
                @foreach ( $findVenteDetail->venteProduit as $item)
                    
                <article class="bg-gray-100 p-2 flex max-w-xl flex-col items-start justify-between">
                    <div class="flex items-center gap-x-4 text-xs">
                    <time class="text-gray-500">{{$item->created_at}}</time>
                    <label class="relative z-10 rounded-full bg-red-100 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">{{$findVenteDetail->type}}</label>
                    </div>
                    <div class="group relative">
                    <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                        <div>
                        <span class="absolute inset-0"></span>
                        {{$item->produit->marque->categorie->libele}} {{$item->produit->marque->libele}} {{$item->produit->libele}} {{$item->produit->etat}}
                        </div>
                    </h3>
                    <!-- <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">Illo sint voluptas. Error voluptates culpa eligendi. Hic vel totam vitae illo. Non aliquid explicabo necessitatibus unde. Sed exercitationem placeat consectetur nulla deserunt vel. Iusto corrupti dicta.</p> -->
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                    <img src="{{asset('storage/produit/'.$item->produit->image)}}" alt="" class="size-14 rounded-sm bg-gray-50">
                    <div class="text-sm/6">
                        <p class="font-semibold text-gray-900">
                        <a href="#">
                            <span class="absolute inset-0"></span>
                            {{$item->quantite}} pc
                        </a>
                        </p>
                        <p class="text-gray-600">@formaMille($item->prixT) Fc</p>
                    </div>
                    </div>
                </article>

                @endforeach

            </div>
        </div>
    </section>