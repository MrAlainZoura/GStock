

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
            @endphp
        @foreach ($data as $key=>$item )

        @foreach ($item->venteProduit as $k=>$v )
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{$v->vente->code}}
                </th>
                <td class="px-6 py-4">
                {{$v->produit->marque->categorie->libele }} {{$v->produit->marque->libele }} {{$v->produit->libele }} 
                </td>
                <td class="px-6 py-4">
                {{ $v->quantite }} 
                </td>
                <td class="px-6 py-4">
                    @php
                        $recette +=$v->prixT;
                    @endphp
                @formaMille($v->prixT) Fc
                </td>
                <td class="px-6 py-4">
                {{$v->vente->type}}
                </td>
                <td class="px-6 py-4">
                {{$v->vente->user->name}}
                </td>
            </tr>
           
            @endforeach
            @endforeach
            <tr>
                
                <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $period }}
                </th>
                <td></td>
                <td></td>
                <th scope="row" class="px-6 py-4 text-xl uppercase text-gray-900 whitespace-nowrap dark:text-white">
                     @formaMille( $recette ) Fc
                </th>
            </tr>
        </tbody>
    </table>
</div>