
<table id="search-table" class="w-70">
    <thead>
        <tr>
            <th>
                <span class="flex items-center max-w-sm">
                    Nom
                    <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                    </svg>
                </span>
            </th>
            <th>
                <span class="flex items-center max-w-sm">
                    Fonction
                    <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                    </svg>
                </span>
            </th>
            <th>
                <span class="flex items-center max-w-sm">
                    Grade / Etude
                    <svg class="w-3 h-3 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z"/>
                    </svg>
                </span>
            </th>
            <th>
                <span class="flex items-center max-w-sm">
                    Affectation
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
                    @if ($item->image!=null)
                        <img class="w-10 h-10 rounded-full" src="{{asset('storage/users/'.$item->image)}}" alt="Small avatar">
                    @else
                        <img class="w-10 h-10 rounded-full" src="{{asset('svg/man.svg')}}" alt="Small avatar">
                    @endif
                    <div>
                        {{$item->name}}               
                        {{$item->postnom}}               
                        {{$item->prenom}}  
                        <br/>             
                        {{$item->email}}               
                    </div>
                </div>
            </td>
            <td >
            {{$item->fonction}}  
            </td>
            <td >
            {{$item->niveauEtude}}  
            {{$item->option}}  
                
            </td>
            <td>
                @if(count($item->depotUser)>0)
                    {{$item->depotUser[0]->depot->libele}} 
                @endif
            </td>
            <td> 
                @include('composant.actionLink', ['itemName'=>$item->name,'seeRoute'=>'user.show','seeParam'=>"$item->name ".$item->id*6789012345, 'deleteRoute'=>"user.destroy",'deleteParam'=>"$item->name ".$item->id*6789012345, 'editeRoute'=>"user.edit",'editParam'=>"$item->name ".$item->id*6789012345])
            </td>
           
        </tr>
        @endforeach

    </tbody>
</table>

@include('composant.modalDelete')


<script>
    
if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
    const dataTable = new simpleDatatables.DataTable("#search-table", {
        searchable: true,
        paging: true,
        perPage: 5,
        perPageSelect: [5, 10, 15, 20, 25, 50,100, 200, 300, 400, 500],
        sortable: true
    });
}
   //modal suppression item
    const deleteLink = document.querySelectorAll('#linkDelete');

    deleteLink.forEach(link => {
    link.addEventListener('click', (event) => {
    event.preventDefault();
    const hrefClicked = event.currentTarget.getAttribute('href');
    const formDelete =document.getElementById('deleteForm');
    const textDeleteItem =document.getElementById('textDeleteItem');
    const itemName = event.currentTarget.getAttribute('itemName') ;
    textDeleteItem.textContent= `Confirmer la suppression de (d') ${itemName}`;
    formDelete.setAttribute('action',hrefClicked);
  });
});
</script>