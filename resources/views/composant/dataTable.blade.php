
<table id="search-table" class="w-70">
    <thead>
        <tr>
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
                    Catégorie
                    <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                    </svg>
                </span>
            </th>
            <th>
                <span class="flex items-center max-w-sm">
                    Stock
                    <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                    </svg>
                </span>
            </th>
            <th>
                <span class="flex items-center">
                    Description
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

        @foreach ($data as $key=>$item )
        <tr>
            <td class="font-medium text-gray-900  dark:text-white">
                <div class="flex gap-2 sm:bloc">
                    @if ($item->produit->image == null)
                        <img class="w-10 h-10 rounded" src="{{asset('produit/prodDefaut.jpeg')}}" alt="Produit">
                    @else
                        <img class="w-10 h-10 rounded" src="{{asset('uploads/produit/'.$item->produit->image)}}" alt="Produit">
                    @endif
                    {{$item->produit->libele}} <br/>
                    {{$item->produit->etat}}
                </div>
            </td>
            <td >
                {{$item->produit->marque->categorie->libele}}
                {{$item->produit->marque->libele}}
            </td>
            <td>
                
            {{$item->quantite}} 
                    @if($item->quantite >=2 )
                    pièces
                    @elseif ($item->quantite==null) 
                    0 pièce
                    @else
                    pièce
                    @endif
                    <br/>
                {{$item->produit->prix}} $
            </td>
            <td > 
                <div class="max-w-sm bg-red-100">
                    {{$item->produit->description}}
                </div> 
            </td>
           <td>
               @include('composant.actionLink', ['itemName'=>$item->produit->libele,'seeRoute'=>'prod.show','seeParam'=>450*$item->produit->id, 'deleteRoute'=>"prod.destroy",'deleteParam'=>450*$item->produit->id, 'editeRoute'=>"prod.edit",'editParam'=>$item->produit->id*450])
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
  @include('composant.modalDelete')
<script>
    


 //modal suppression item

    @if(isset($item))
        const depot = @json($item);
        const depot_id = depot.depot_id;
    @else
        const depot = null;
        const depot_id = null;
    @endif
 

    //const deleteLink = document.querySelectorAll('#linkDelete');
    // deleteLink.forEach(link => {
    //     link.addEventListener('click', (event) => {
    //         event.preventDefault();
    //         const hrefClicked = event.currentTarget.getAttribute('href');
    //         const formDelete =document.getElementById('deleteForm');
    //         const textDeleteItem =document.getElementById('textDeleteItem');
    //         const itemName = event.currentTarget.getAttribute('itemName');
    //         textDeleteItem.textContent= `Confirmer la suppression du produit ${itemName}`;

    //         const inputDepotId = document.createElement('input');
    //                 inputDepotId.type = "text";
    //                 inputDepotId.name = "depot_id"; // important si tu veux que ce champ soit envoyé avec le formulaire
    //                 inputDepotId.value = depot_id;  // tu peux assigner la valeur ici si elle existe
    //                 inputDepotId.hidden = true;
    //                 inputDepotId.id="depot_id";

    //         if(!document.getElementById('depot_id')){
    //             formDelete.appendChild(inputDepotId);
    //         }
    //         formDelete.setAttribute('action',hrefClicked);

    //     });
    // });
</script>