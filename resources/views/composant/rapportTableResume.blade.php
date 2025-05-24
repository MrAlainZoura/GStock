
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Libele
                </th>
                <th scope="col" class="px-6 py-3">
                    Categorie
                </th>
                <th scope="col" class="px-6 py-3">
                  Entrée
                </th>
                <th scope="col" class="px-6 py-3">
                    Transf
                </th>
                <th scope="col" class="px-6 py-3">
                    Vendu
                </th>
                <th scope="col" class="px-6 py-3">
                    Reste
                </th>
                
            </tr>
        </thead>
        <tbody>
        
        @foreach($data as $k=>$v)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{$v['libele']}}
                </th>
                <td class="px-6 py-4">
                    {{$v['cat']}}
                </td>
                <td class="px-6 py-4">
                    {{$v['enter']}}
                </td>
                <td class="px-6 py-4">
                    {{$v['trans']}}
                </td>
                <td class="px-6 py-4">
                    {{$v['vente']}}
                </td>
                <td class="px-6 py-4">
                    {{$v['rest']}}
                </td>
            </tr>
        @endforeach
            
        </tbody>
    </table>
</div>