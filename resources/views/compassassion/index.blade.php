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
    

    <section class="p-10 gap-5 w-full">
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Liste de compassassion de {{$depot->libele}} </h1>
        </div>
        <table id="search-table" class="w-70">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center max-w-sm">
                            Date
                            <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                            </svg>
                        </span>
                    </th>
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
                            Precedent
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
                        <span class="flex items-center">
                        Paiement
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
            
                @foreach ($compassassion as $key=>$item )
                <tr>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="flex gap-2 sm:bloc">
                            {{$item->compassassion[0]->created_at}}
                                        
                        </div>
                        <div class="flex sm:bloc">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" transform="matrix(1, 0, 0, 1, 0, 0)rotate(90)">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                                <g id="SVGRepo_iconCarrier"> <path d="M12 4.5L17 9.5M12 4.5L7 9.5M12 4.5L12 11M12 14.5C12 16.1667 11 19.5 7 19.5" stroke="#5b5c62" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </g>
                            </svg>
                            {{$item->created_at->format('Y/m/d')}}
                        </div>
                    </td>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="flex gap-2 sm:bloc">
                            @if($item->user!=null)
                                {{$item->user->name}}
                            @else
                                Utilisateur à problème
                            @endif                 
                        </div>
                    </td>
                    <td class="font-medium text-gray-900  dark:text-white">
                        <div class="flex gap-2 sm:bloc">
                            {{$item->client->name }}<br/>
                            {{$item->client->tel }}                
                        </div>
                    </td>
                    <td >
                    <div>
                            <!-- {{$item->client->name }}<br/> -->
                            <!-- {{$item->client->tel }}               -->
                            @foreach ( $item->venteProduit as $val)
                                <label class="block">
                                    {{ $val->produit ? $val->produit->libele : "Produit retiré" }} : {{$val->quantite}} {{($val->quantite> 1 )?$val->produit->unite."s":$val->produit->unite }}
                                </label>
                            @endforeach
                        </div>
                    </td>
                    <td >
                            @php
                                $paiment = 0;
                            @endphp
                            @foreach ( $item->compassassion as $comp)
                                @php
                                    $prix = preg_replace('/[^\d.]/', '', $comp->prixT);
                                    if (is_numeric($prix)) {
                                        $paiment += (float) $prix;
                                    }
                                @endphp
                                <label class="block"> 
                                    @if($comp->produit)
                                    {{$comp->produit->libele}} : {{$comp->quantite}} {{($comp->quantite> 1 )?$comp->produit->unite."s":$comp->produit->unite }}
                                    @else 
                                        Produit retiré : {{$comp->quantite}}  {{($comp->quantite> 1 )?$comp->produit->unite."s":$comp->produit->unite }}
                                    @endif   
                                </label>
                            @endforeach
                    </td>
                    <td >
                        @formaMille($paiment)
                        @if($item->devise) 
                            {{ $item->devise->libele }} 
                        @endif
                        <input type="text" class="hidden totalPaie"  value="{{ $paiment *$item->updateTaux }}">
                    </td>

                    <td>
                        @include('composant.actionLink', ['itemName'=>"Vente_".$item->user->name."_".$item->created_at."_".$prix . $item->devise->libele,'seeRoute'=>'venteShow','seeParam'=>["vente"=>56745264509*$item->id, "depot"=>$item->depot_id*12], 'deleteRoute'=>"compDelete",'deleteParam'=>$comp->id, 'editeRoute'=>"compCreate",'editParam'=>['depot'=>$item->depot->libele, 'vente_id'=>$item->id]])
                    </td>
                </tr>
            
                @endforeach
            </tbody>
        </table>
       
    </section>
    

    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=>$depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

