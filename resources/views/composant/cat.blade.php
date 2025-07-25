

<div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    @if($image)
        <img class="rounded-t-lg w-full h-60" src="{{asset('uploads/cat/'.$image)}}" alt="" />
    @endif
    <div class="p-5">
        <a href="{{ route('cat-pro.edit',['cat_pro' => urlencode(json_encode([$libele, $id*432]))])}}">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Catégorie {{$libele}}</h5>
        </a>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">L'une des catégories disponibles dans nos stocks et elle compte {{$marque}} marques dont </p>
        
        <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400">
            @foreach($tab as $k=>$v)
            <li class="flex items-center">
                <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                </svg>
                {{$v->libele}}
            </li>
            @endforeach
        </ul>

        <a href="{{ route('cat-pro.edit',['cat_pro' => urlencode(json_encode([$libele, $id*432]))])}}" class="mt-5 inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Voir plus
             <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
            </svg>
        </a>
    </div>
</div>
