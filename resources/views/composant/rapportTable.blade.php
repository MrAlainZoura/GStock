@php
    $cdfPrime = $depot->use_cdf;
@endphp

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
                $restePaiementTranche = 0;
                $restePaiementTrancheFc = 0;
            @endphp
            @foreach ($data as $key=>$item )
                @php

                    $resteSolde = ($item->paiement[0]->reference_devise == null)
                                ? (float) $item->paiement->sortByDesc('created_at')->first()->solde * (float)$item->updateTaux
                                : (float) $item->paiement->sortByDesc('created_at')->first()->solde;
                    $restePaiementTrancheFc += $resteSolde;
                @endphp
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
                            $prixT = ($item->paiement[0]->reference_devise == null)
                                    ? (float)$v->prixT * (float)$item->updateTaux
                                    : (float)$v->prixT;
                            $recetteFc +=$prixT;
                            $prixShow = $cdfPrime ? $prixT : $prixT /(float) $item->updateTaux;
                            $monnaie = $cdfPrime ? "CDF" : $item->devise->libele;
                        @endphp
                        @formaMille($prixShow) {{ $monnaie }}
                        </td>
                        <td class="px-6 py-4">
                            {{$item->updateTaux}} CDF
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
                                    @php
                                        $prixComp = ($v->paiement->last()->reference_devise == null)
                                            ? (float)$comp->prixT * (float)$v->updateTaux
                                            : $comp->prixT ;
                                        $monnaieComp = $cdfPrime ? 'CDF' : $v->devise->libele;
                                    @endphp
                                    <li>
                                        {{ $comp->produit->libele }} {{ $comp->quantite }} pc 
                                        <span class="text-xl text-gray-600">→</span>
                                        {{$cdfPrime ?(float) $prixComp : (float)$prixComp /(float) $v->updateTaux}} {{$monnaie}}</li>
                                @endforeach
                            </ol> 
                            <br> 
                            Contre (ancien article)
                            <ol class="ps-5 mt-2 space-y-1 list-decimal list-inside">
                            @foreach ($v->venteProduit as $anV)
                                    @php
                                        $prixAnV =($v->paiement->first()->reference_devise == null)
                                            ? (float)$anV->prixT * (float)$v->updateTaux
                                            : $anV->prixT ;
                                        $monnaieAnV = $cdfPrime ? 'CDF' : $v->devise->libele;
                                    @endphp
                                <li>
                                    {{ $anV->produit->libele }} {{ $anV->quantite }}pc 
                                    <span class="text-xl text-gray-600">→</span>
                                    {{$cdfPrime ? $prixAnV : $prixAnV/(float)$v->updateTaux }} {{$monnaieAnV }}
                                </li>
                            @endforeach 
                            </ol>
                        </td>
                        <td class="px-5 py-4">
                            @php
                                $paiementComp =0;
                                $oldnet = ($v->paiement->sortBy('created_at')->first()->reference_devise == null)
                                    ?(float) $v->paiement->sortBy('created_at')->first()->net * (float) $v->updateTaux
                                    :(float) $v->paiement->sortBy('created_at')->first()->net;
                                foreach ($v->paiement as $key => $val) {
                                    $avance = ( $val->reference_devise == null)
                                            ? (float) $val->avance * (float) $v->updateTaux
                                            : (float) $val->avance;
                                    $paiementComp +=$avance;
                                }
                                $ajoutBrut = ($oldnet < $paiementComp)
                                    ? $avance
                                    : 0 ;
                                $ajout = $cdfPrime ? $ajoutBrut : $ajoutBrut/(float) $v->updateTaux;
                                $recette += (float) $ajoutBrut;
                                $recetteFc += (float) $ajoutBrut;
                            @endphp
                            {{ $oldnet}} + {{ $ajout}}<br> {{ $paiementComp }}
                            {{ $v->paiement->sortByDesc('created_at')->first()->net }} 
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
                     @php
                        $recetteFc -= (float)$restePaiementTrancheFc;
                     @endphp
                    @formaMille( (float)$recetteFc) cdf<br>
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
                @if ((float)$restePaiementTrancheFc >0)
                    <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                        Reste P-Tranche
                    </th>
                    <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                        @formaMille( (float)$restePaiementTrancheFc) Fc<br>
                    @if (count($data) >0)
                        @foreach ($data[0]->depot->devise as $cle=>$dev )
                            @formaMille((float) $restePaiementTrancheFc/ (float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                        @endforeach
                    @elseif(count($compassassion)>0)
                     @foreach ($compassassion[0]->depot->devise as $cle=>$dev )
                            @formaMille((float) $restePaiementTrancheFc/ (float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                        @endforeach
                    @endif
                    </th>
                @endif
            </tr>
        </tbody>
    </table>
</div>