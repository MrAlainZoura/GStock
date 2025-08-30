

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    code
                </th>
                <th scope="col" class="px-6 py-3">
                    produit
                </th>
                <th scope="col" class="px-6 py-3">
                    Quantité
                </th>
                <th scope="col" class="px-6 py-3">
                  Montant
                </th>
                <th scope="col" class="px-6 py-3">
                  Taux vente
                </th>
                <th scope="col" class="px-6 py-3">
                    Lieu
                </th>
                <th scope="col" class="px-6 py-3">
                    Vendeur
                </th>
            </tr>
        </thead>
        <tbody>
            @php
                $recette =0;
                $recetteFc =0;
            @endphp
        @foreach ($data as $key=>$item )

        @foreach ($item->venteProduit as $k=>$v )
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ $key+1 }}. {{$v->vente->code}}
                </th>
                <td class="px-6 py-4">
                {{$v->produit->marque->categorie->libele }} {{$v->produit->marque->libele }} {{$v->produit->libele }} 
                </td>
                <td class="px-6 py-4">
                {{ $v->quantite }} 
                </td>
                <td class="px-6 py-4">
                    @php
                        $recette +=(float)$v->prixT;
                        $recetteFc += (float)$v->prixT * (float)$item->updateTaux;
                    @endphp
                @formaMille((float)$v->prixT) {{ $item->devise->libele }}
                </td>
                <td class="px-6 py-4">
                    {{$item->updateTaux}}Fc
                </td>
                <td class="px-6 py-4">
                    {{$v->vente->type}}
                </td>
                <td class="px-6 py-4">
                @if($v->vente->user != null)
                    {{$v->vente->user->name}}
                @else
                    Utilisateur à problème
                @endif
                </td>
            </tr>
           
            @endforeach
            @endforeach
            @if (count($compassassion) >0)
            <tr>
                <th scope="col" colspan="4" class="px-6 py-3">
                    Contre valeur - compassassion
                </th>
            </tr>
            @foreach ($compassassion as $k=>$v )
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $k+1 }}. {{$v->code}}
                    </th>
                    <td class="py-2" colspan="2">
                        Echangé(s)
                        <ol class="ps-2 mt-2 space-y-1 list-decimal list-inside">
                            @foreach ($v->compassassion as $comp)
                                <li>
                                    {{ $comp->produit->libele }} {{ $comp->quantite }} pc 
                                    <span class="text-xl text-gray-600">→</span>
                                    {{ $comp->prixT }} {{ $v->devise->libele }}</li>
                            @endforeach
                        </ol>
                           <br> 
                        Contre (ancien article)
                        <ol class="ps-5 mt-2 space-y-1 list-decimal list-inside">
                           @foreach ($v->venteProduit as $anV)
                           <li>
                                {{ $anV->produit->libele }} {{ $anV->quantite }}pc 
                                <span class="text-xl text-gray-600">→</span>
                                {{ $anV->prixT }} {{ $v->devise->libele }}
                            </li>
                           @endforeach 
                        </ol>
                    </td>
                    <td class="px-5 py-4">
                        {{ $v->paiement->sortBy('created_at')->first()->net}} + {{ $v->paiement->sortByDesc('created_at')->first()->avance }}<br>
                        {{ $v->paiement->sortByDesc('created_at')->first()->net }} {{ $v->devise->libele }}
                         @php
                            $recette +=(float) $v->paiement->sortByDesc('created_at')->first()->avance;
                            $recetteFc += (float)$v->paiement->sortByDesc('created_at')->first()->avance* (float)$v->updateTaux;
                        @endphp
                    </td>
                    <td class="px-6 py-4">
                        {{$v->updateTaux}}Fc
                    </td>
                    <td class="px-6 py-4">
                        Shop
                    </td>
                    <td class="px-6 py-4">
                    @if($v->user != null)
                        {{$v->user->name}}
                    @else
                        Utilisateur à problème
                    @endif
                    </td>
                </tr>
               
                @endforeach
            @endif
            <tr>
                
                <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $period }}
                </th>
                <td></td>
                <td></td>
                <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                     @formaMille( (float)$recetteFc) Fc<br>
                    @if (count($data) >0)
                        @foreach ($data[0]->depot->devise as $cle=>$dev )
                            @formaMille((float) $recetteFc/ (float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                        @endforeach
                    @elseif(count($compassassion)>0)
                     @foreach ($compassassion[0]->depot->devise as $cle=>$dev )
                            @formaMille((float) $recetteFc/ (float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                        @endforeach
                    @endif
                </th>
            </tr>
        </tbody>
    </table>
</div>