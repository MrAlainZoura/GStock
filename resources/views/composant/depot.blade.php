
<a href="{{$route}}" class="flex flex-col items-center bg-red-100 border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
    <img class="object-cover w-full rounded-t-lg h-96 md:h-full md:w-48 md:rounded-none md:rounded-s-lg" src="{{asset('img/depot.jpg')}}" alt="">
    <div class="flex flex-col justify-between p-4 leading-normal">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Dépôt {{$libele}}</h5>
        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Effectuer vos opération pour le compte de {{$libele}}. L'approvisionnement de stock, transfert ou la vente des marchandises.</p>
    </div>
</a>
