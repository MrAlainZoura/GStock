

<!-- drawer init and show -->
<!-- <div class="text-center">
   <button class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" 
   type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" 
   aria-controls="drawer-navigation">
   Menu
   </button>
</div> -->

<!-- drawer component -->
<div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-navigation-label">
    <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">Menu</h5>
    <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        <span class="sr-only">Close menu</span>
    </button>
  <div class="py-4 overflow-y-auto">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                  <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                     <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                  </svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Approviosionnement</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{route('aproDepot',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/list.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Liste
                     </a>
                  </li>
                 
                  <li>
                  
                     <a href="{{route('approCreate',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/add.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Ajouter
                     </a>
                  </li>
            </ul>
         </li>
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" aria-controls="dropdown-exampleV" data-collapse-toggle="dropdown-exampleV">
                  <img src="{{asset('svg/paie.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Vente</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-exampleV" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{route('venteDepot',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/list.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Liste
                     </a>
                  </li>
                 
                  <li>
                     <a href="{{route('venteCreate', $depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/add.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Ajouter
                     </a>
                  </li>
            </ul>
         </li>
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
             aria-controls="dropdown-example1" data-collapse-toggle="dropdown-example1">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Produits</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example1" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{route('showProduit',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/list.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Liste
                     </a>
                  </li>
                  <li>
                     <a href="{{route('cat-pro.index')}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/cat.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Catégorie
                     </a>
                  </li>
                 
                  <li>
                     <a href="#" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/add.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Ajouter
                     </a>
                  </li>
              
                  
                  
            </ul>
         </li>
         
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700" 
            aria-controls="dropdown-example2" data-collapse-toggle="dropdown-example2">
                  <img src="{{asset('svg/transfert.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Transfert</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example2" class="hidden py-2 space-y-2">
            <li>
                     <a href="{{route('transDepot',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/list.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Liste
                     </a>
                  </li>
                 
                  <li>
                     <a href="{{route('transCreate',$depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/add.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Ajouter
                     </a>
                  </li>
            </ul>
         </li>
         
         
         <!-- <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Inbox</span>
               <span class="inline-flex items-center justify-center w-3 h-3 p-3 ms-3 text-sm font-medium text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">3</span>
            </a>
         </li> -->

         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
             aria-controls="dropdown-example3" data-collapse-toggle="dropdown-example3">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Users</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example3" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{route('user.index')}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/list.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Liste
                     </a>
                  </li>
                 
                  <li>
                     <a href="{{route('user.create')}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <img src="{{asset('svg/add.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">
                        Ajouter
                     </a>
                  </li>
            </ul>
         </li>
         <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
             aria-controls="dropdown-example5" data-collapse-toggle="dropdown-example5">
             <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

<!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                  <svg fill="#000000" height="24px" width="24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 463 463" xml:space="preserve" stroke="#000000" stroke-width="0.0046300000000000004">
                     <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                     <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                     <g id="SVGRepo_iconCarrier"> <g> <path d="M367.5,32h-57.734c-3.138-9.29-11.93-16-22.266-16h-24.416c-7.41-9.965-19.148-16-31.584-16 c-12.435,0-24.174,6.035-31.585,16H175.5c-10.336,0-19.128,6.71-22.266,16H95.5C78.131,32,64,46.131,64,63.5v368 c0,17.369,14.131,31.5,31.5,31.5h272c17.369,0,31.5-14.131,31.5-31.5v-368C399,46.131,384.869,32,367.5,32z M175.5,87h112 c7.023,0,13.332-3.101,17.641-8H352v337H111V79h46.859C162.168,83.899,168.477,87,175.5,87z M175.5,31h28.438 c2.67,0,5.139-1.419,6.482-3.727C214.893,19.588,222.773,15,231.5,15c8.728,0,16.607,4.588,21.079,12.272 c1.343,2.308,3.813,3.728,6.482,3.728H287.5c4.687,0,8.5,3.813,8.5,8.5v24c0,4.687-3.813,8.5-8.5,8.5h-112 c-4.687,0-8.5-3.813-8.5-8.5v-24C167,34.813,170.813,31,175.5,31z M384,431.5c0,9.098-7.402,16.5-16.5,16.5h-272 c-9.098,0-16.5-7.402-16.5-16.5v-368C79,54.402,86.402,47,95.5,47H152v16.5c0,0.168,0.009,0.333,0.013,0.5H103.5 c-4.143,0-7.5,3.358-7.5,7.5v352c0,4.142,3.357,7.5,7.5,7.5h256c4.143,0,7.5-3.358,7.5-7.5v-352c0-4.142-3.357-7.5-7.5-7.5h-48.513 c0.004-0.167,0.013-0.332,0.013-0.5V47h56.5c9.098,0,16.5,7.402,16.5,16.5V431.5z"/> <path d="M231.5,47c1.979,0,3.91-0.8,5.3-2.2c1.4-1.39,2.2-3.33,2.2-5.3c0-1.97-0.8-3.91-2.2-5.3c-1.39-1.4-3.32-2.2-5.3-2.2 c-1.98,0-3.91,0.8-5.3,2.2c-1.4,1.39-2.2,3.32-2.2,5.3s0.8,3.91,2.2,5.3C227.59,46.2,229.52,47,231.5,47z"/> <path d="M183.5,159h136c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-136c-4.143,0-7.5,3.358-7.5,7.5S179.357,159,183.5,159z"/> <path d="M183.5,239h136c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-136c-4.143,0-7.5,3.358-7.5,7.5S179.357,239,183.5,239z"/> <path d="M183.5,319h24c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-24c-4.143,0-7.5,3.358-7.5,7.5S179.357,319,183.5,319z"/> <path d="M183.5,199h136c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-136c-4.143,0-7.5,3.358-7.5,7.5S179.357,199,183.5,199z"/> <path d="M183.5,279h32c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-32c-4.143,0-7.5,3.358-7.5,7.5S179.357,279,183.5,279z"/> <path d="M183.5,359h32c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-32c-4.143,0-7.5,3.358-7.5,7.5S179.357,359,183.5,359z"/> <path d="M143.5,159h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,159,143.5,159z"/> <path d="M143.5,239h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,239,143.5,239z"/> <path d="M143.5,319h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,319,143.5,319z"/> <path d="M143.5,199h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,199,143.5,199z"/> <path d="M143.5,279h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,279,143.5,279z"/> <path d="M143.5,359h8c4.143,0,7.5-3.358,7.5-7.5s-3.357-7.5-7.5-7.5h-8c-4.143,0-7.5,3.358-7.5,7.5S139.357,359,143.5,359z"/> <path d="M279.5,264c-26.191,0-47.5,21.309-47.5,47.5s21.309,47.5,47.5,47.5c10.583,0,20.367-3.482,28.272-9.357 c0.074-0.052,0.155-0.088,0.228-0.143c0.2-0.15,0.389-0.309,0.57-0.474C319.771,340.329,327,326.747,327,311.5 C327,285.309,305.691,264,279.5,264z M272,279.883V304h-24.117C250.708,292.094,260.094,282.708,272,279.883z M247.883,319h27.867 l16.719,22.292c-3.976,1.737-8.36,2.708-12.969,2.708C264.161,344,251.279,333.315,247.883,319z M304.463,332.284L287,309v-29.117 c14.315,3.396,25,16.278,25,31.617C312,319.398,309.165,326.646,304.463,332.284z"/> </g> </g>
                  </svg>
                  <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Rapport</span>
                  <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                  </svg>
            </button>
            <ul id="dropdown-example5" class="hidden py-2 space-y-2">
                  <li>
                     <a href="{{route('rapport.jour', $depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">


                  <svg fill="#ababab" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 36.447 36.447" xml:space="preserve" stroke="#ababab">
                     <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                     <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                     <g id="SVGRepo_iconCarrier"> <g> <g> <path d="M30.224,3.948h-1.098V2.75c0-1.516-1.197-2.75-2.67-2.75c-1.474,0-2.67,1.234-2.67,2.75v1.197h-2.74V2.75 c0-1.516-1.197-2.75-2.67-2.75c-1.473,0-2.67,1.234-2.67,2.75v1.197h-2.74V2.75c0-1.516-1.197-2.75-2.67-2.75 c-1.473,0-2.67,1.234-2.67,2.75v1.197H6.224c-2.343,0-4.25,1.907-4.25,4.25v24c0,2.343,1.907,4.25,4.25,4.25h24 c2.344,0,4.25-1.907,4.25-4.25v-24C34.474,5.854,32.567,3.948,30.224,3.948z M25.286,2.75c0-0.689,0.525-1.25,1.17-1.25 c0.646,0,1.17,0.561,1.17,1.25v4.895c0,0.689-0.524,1.25-1.17,1.25c-0.645,0-1.17-0.561-1.17-1.25V2.75z M17.206,2.75 c0-0.689,0.525-1.25,1.17-1.25s1.17,0.561,1.17,1.25v4.895c0,0.689-0.525,1.25-1.17,1.25s-1.17-0.561-1.17-1.25V2.75z M9.125,2.75 c0-0.689,0.525-1.25,1.17-1.25s1.17,0.561,1.17,1.25v4.895c0,0.689-0.525,1.25-1.17,1.25s-1.17-0.561-1.17-1.25V2.75z M31.974,32.198c0,0.965-0.785,1.75-1.75,1.75h-24c-0.965,0-1.75-0.785-1.75-1.75v-22h27.5V32.198z"/> <path d="M11.062,29.443c0.298,0,0.603-0.039,0.911-0.115c0.754,0.826,1.823,1.309,2.941,1.309c0.623,0,1.232-0.146,1.788-0.427 c0.694,0.534,1.538,0.823,2.42,0.823c1.375,0,2.658-0.729,3.381-1.893c1.889,0.1,3.466-1.414,3.466-3.287 c0-0.828-0.313-1.58-0.822-2.159c0.599-0.751,0.96-1.697,0.96-2.729c0-2.432-1.979-4.41-4.41-4.41 c-1.959,0-3.621,1.287-4.193,3.059c-0.224,0.095-0.442,0.202-0.651,0.337c-0.739-0.707-1.708-1.104-2.733-1.104 c-1.703,0-3.225,1.112-3.769,2.701c-1.869,0.34-3.276,1.979-3.276,3.907C7.074,27.656,8.863,29.443,11.062,29.443z M21.696,17.78 c1.756,0,3.187,1.429,3.187,3.187c0,0.752-0.269,1.437-0.709,1.979c-0.228-0.116-0.466-0.215-0.722-0.277 c-0.461-1.968-2.222-3.4-4.27-3.4c-0.055,0-0.107,0.01-0.164,0.012C19.579,18.385,20.562,17.78,21.696,17.78z M10.871,22.717 l0.456-0.033l0.099-0.446c0.278-1.255,1.412-2.165,2.694-2.165c0.844,0,1.634,0.393,2.168,1.072l0.389,0.496l0.484-0.402 c0.586-0.489,1.286-0.748,2.021-0.748c1.588,0,2.937,1.197,3.136,2.785l0.062,0.486l0.488,0.045 c1.069,0.102,1.875,0.981,1.875,2.047c0,1.14-0.928,2.068-2.068,2.068c-0.125,0-0.253-0.014-0.395-0.041l-0.466-0.09l-0.202,0.428 c-0.466,0.98-1.42,1.59-2.492,1.59c-0.718,0-1.402-0.279-1.923-0.787l-0.353-0.342l-0.41,0.271 c-0.456,0.301-0.982,0.46-1.521,0.46c-0.887,0-1.701-0.418-2.231-1.147l-0.27-0.369l-0.431,0.154 c-0.322,0.114-0.623,0.17-0.919,0.17c-1.524,0-2.764-1.24-2.764-2.764C8.298,24.02,9.428,22.816,10.871,22.717z"/> <path d="M27.593,21.148c0,0.119,0.095,0.213,0.213,0.213h1.354c0.119,0,0.213-0.094,0.213-0.213c0-0.118-0.094-0.213-0.213-0.213 h-1.354C27.687,20.934,27.593,21.03,27.593,21.148z"/> <path d="M26.987,18.307c0.036,0,0.072-0.01,0.105-0.028l1.174-0.676c0.102-0.06,0.137-0.189,0.078-0.292 c-0.059-0.102-0.188-0.136-0.291-0.078l-1.173,0.676c-0.103,0.06-0.138,0.189-0.078,0.292 C26.842,18.268,26.913,18.307,26.987,18.307z"/> <path d="M24.749,16.071c0.074,0,0.146-0.038,0.186-0.106l0.68-1.172c0.059-0.103,0.022-0.232-0.078-0.292 c-0.104-0.059-0.232-0.023-0.293,0.078l-0.678,1.172c-0.06,0.102-0.023,0.232,0.078,0.291 C24.677,16.061,24.714,16.071,24.749,16.071z"/> <path d="M21.696,15.252L21.696,15.252c0.117,0,0.213-0.096,0.213-0.213v-1.354c0-0.118-0.095-0.214-0.213-0.214 c-0.117,0-0.213,0.095-0.213,0.214l-0.002,1.354C21.481,15.155,21.577,15.252,21.696,15.252z"/> <path d="M18.458,15.964c0.038,0.068,0.111,0.106,0.184,0.106c0.036,0,0.073-0.008,0.106-0.027 c0.102-0.061,0.138-0.189,0.078-0.292l-0.676-1.174c-0.058-0.101-0.189-0.137-0.291-0.078c-0.102,0.059-0.137,0.189-0.078,0.292 L18.458,15.964z"/> <path d="M16.3,18.278c0.033,0.021,0.07,0.028,0.106,0.028c0.073,0,0.145-0.039,0.185-0.106c0.059-0.103,0.024-0.232-0.078-0.291 l-1.173-0.678c-0.102-0.06-0.232-0.024-0.291,0.078c-0.059,0.103-0.024,0.232,0.078,0.292L16.3,18.278z"/> <path d="M28.053,25.064c0.034,0.021,0.071,0.029,0.106,0.029c0.074,0,0.146-0.039,0.186-0.106 c0.059-0.103,0.023-0.231-0.078-0.292l-1.174-0.676c-0.102-0.061-0.232-0.025-0.29,0.078c-0.06,0.102-0.024,0.231,0.078,0.291 L28.053,25.064z"/> </g> </g> </g>
                  </svg>                        
                     Journalier
                     </a>
                  </li>
                 
                  <li>
                     <a href="{{route('rapport.mois', $depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                     <svg fill="#c3c1c1" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 31.582 31.582" xml:space="preserve" stroke="#c3c1c1">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <g> <g> <path d="M26.018,6.8h-2.789L16.53,1.101C16.567,1.008,16.59,0.907,16.59,0.8c0-0.441-0.358-0.8-0.8-0.8S14.99,0.358,14.99,0.8 c0,0.106,0.022,0.208,0.061,0.301L8.353,6.8H5.563C4.371,6.8,3.4,7.771,3.4,8.963v20.456c0,1.193,0.97,2.163,2.163,2.163h18.013 l4.605-4.605V8.963C28.181,7.77,27.209,6.8,26.018,6.8z M15.384,1.474c0.121,0.073,0.255,0.127,0.406,0.127 s0.285-0.054,0.406-0.127l6.26,5.326H9.124L15.384,1.474z M14.973,23.207c-0.183,0.425-0.433,0.771-0.747,1.038 c-0.314,0.269-0.688,0.464-1.116,0.59c-0.431,0.127-0.887,0.188-1.369,0.188c-0.557,0-1.058-0.049-1.504-0.148 c-0.445-0.1-0.831-0.277-1.156-0.534c-0.324-0.258-0.574-0.61-0.746-1.063c-0.174-0.449-0.261-1.031-0.261-1.745h2.55 c0,0.252,0.012,0.487,0.039,0.709c0.025,0.219,0.076,0.411,0.15,0.573c0.072,0.162,0.171,0.293,0.297,0.394 c0.127,0.101,0.289,0.149,0.489,0.149c0.335,0,0.587-0.114,0.754-0.346c0.168-0.231,0.252-0.662,0.252-1.291 c0-0.355-0.022-0.649-0.07-0.882c-0.047-0.229-0.126-0.412-0.236-0.543c-0.109-0.131-0.251-0.223-0.425-0.274 c-0.173-0.053-0.391-0.078-0.652-0.078h-0.632v-1.668h0.725c0.441,0,0.74-0.144,0.896-0.426c0.158-0.283,0.237-0.697,0.237-1.242 c0-0.44-0.071-0.764-0.212-0.969c-0.143-0.203-0.365-0.307-0.67-0.307c-0.294,0-0.538,0.109-0.731,0.331 c-0.193,0.22-0.291,0.646-0.291,1.272h-2.36c0-1.11,0.283-1.93,0.85-2.455c0.577-0.545,1.484-0.818,2.723-0.818 c1.102,0,1.917,0.242,2.446,0.725c0.529,0.483,0.795,1.264,0.795,2.346c0,0.564-0.137,1.051-0.409,1.453 c-0.272,0.404-0.666,0.664-1.182,0.779v0.031c0.662,0.146,1.134,0.434,1.418,0.857c0.282,0.425,0.424,1.029,0.424,1.816 C15.248,22.27,15.156,22.782,14.973,23.207z M23.398,21.579c-0.074,0.69-0.236,1.292-0.488,1.802s-0.619,0.909-1.102,1.202 c-0.482,0.295-1.139,0.44-1.966,0.44c-0.841,0-1.501-0.129-1.984-0.385c-0.481-0.257-0.849-0.627-1.101-1.109 c-0.252-0.482-0.412-1.075-0.479-1.777c-0.069-0.703-0.103-1.506-0.103-2.408c0-0.796,0.036-1.542,0.11-2.232 c0.073-0.692,0.236-1.293,0.487-1.803c0.253-0.509,0.619-0.91,1.103-1.204c0.481-0.293,1.138-0.441,1.968-0.441 c0.827,0,1.485,0.13,1.974,0.387c0.488,0.258,0.857,0.627,1.11,1.109c0.251,0.482,0.411,1.078,0.479,1.785 c0.066,0.707,0.1,1.508,0.1,2.398C23.508,20.143,23.472,20.887,23.398,21.579z"/> <path d="M20.69,16.15c-0.071-0.305-0.178-0.518-0.313-0.639c-0.137-0.119-0.313-0.181-0.533-0.181 c-0.223,0-0.399,0.062-0.536,0.181c-0.138,0.121-0.241,0.334-0.313,0.639c-0.074,0.304-0.121,0.716-0.143,1.234 c-0.021,0.52-0.031,1.178-0.031,1.975c0,0.787,0.01,1.438,0.031,1.951c0.021,0.514,0.068,0.924,0.143,1.228 c0.072,0.305,0.178,0.519,0.313,0.637c0.137,0.121,0.313,0.183,0.536,0.183c0.22,0,0.396-0.062,0.533-0.183 c0.137-0.118,0.242-0.332,0.313-0.637c0.074-0.304,0.122-0.713,0.144-1.228c0.021-0.514,0.031-1.164,0.031-1.951 c0-0.797-0.011-1.455-0.031-1.975C20.812,16.866,20.766,16.454,20.69,16.15z"/> </g> </g> </g>
                     </svg>
                        Mensuel
                     </a>
                  </li>
                  <li>
                     <a href="{{route('rapport.annee', $depot)}}" class="flex items-center gap-2 w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
                        <svg fill="#ababab" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#ababab">
                           <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                           <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                           <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M327.726,189.333h1.067c5.89,0,10.667-4.775,10.667-10.667c0-5.891-4.777-10.667-10.667-10.667h-1.067 c-5.89,0-10.667,4.775-10.667,10.667C317.059,184.558,321.835,189.333,327.726,189.333z"/> <path d="M501.333,88h-80.8V34.667c0-5.891-4.776-10.667-10.667-10.667h-64c-5.89,0-10.667,4.775-10.667,10.667V88H176.8V34.667 c0-5.891-4.775-10.667-10.667-10.667h-64c-5.891,0-10.667,4.775-10.667,10.667V88h-80.8C4.775,88,0,92.775,0,98.667v80v298.667 C0,483.225,4.775,488,10.667,488h490.667c5.89,0,10.667-4.775,10.667-10.667V178.667v-80C512,92.775,507.225,88,501.333,88z M356.533,45.333H399.2V88h-42.667V45.333z M112.8,45.333h42.667V88H112.8V45.333z M490.667,168h-98.052 c-5.89,0-10.667,4.775-10.667,10.667c0,5.891,4.776,10.667,10.667,10.667h98.052v277.333H21.333V189.333h241.149 c5.89,0,10.667-4.775,10.667-10.667c0-5.891-4.777-10.667-10.667-10.667H21.333v-58.667h469.333V168z"/> <path d="M158.477,221.333V264H53.333c-5.891,0-10.667,4.776-10.667,10.667v160c0,5.891,4.775,10.667,10.667,10.667h231.619 c5.89,0,10.667-4.775,10.667-10.667V392h163.047c5.89,0,10.667-4.775,10.667-10.667v-160c0-5.891-4.775-10.667-10.667-10.667 H169.143C163.252,210.667,158.477,215.442,158.477,221.333z M100.572,424H64v-32h36.572V424z M100.572,370.667H64v-32h36.572 V370.667z M100.572,317.333H64v-32h36.572V317.333z M158.477,424h-36.572v-32h36.572V424z M158.477,370.667h-36.572v-32h36.572 V370.667z M158.477,317.333h-36.572v-32h36.572V317.333z M411.428,232H448v32h-36.572V232z M411.428,285.333H448v32h-36.572 V285.333z M411.428,338.667H448v32h-36.572V338.667z M353.524,232h36.571v32h-36.571V232z M353.524,285.333h36.571v32h-36.571 V285.333z M353.524,338.667h36.571v32h-36.571V338.667z M295.619,232h36.572v32h-36.572V232z M295.619,285.333h36.572v32h-36.572 V285.333z M295.619,338.667h36.572v32h-36.572V338.667z M216.382,424H179.81v-32h36.572V424z M216.382,370.667H179.81v-32h36.572 V370.667z M274.286,424h-36.572v-32h36.572V424z M237.714,232h36.572v32h-36.572V232z M237.714,285.333h36.572v32h-36.572 V285.333z M237.714,338.667h36.572v32h-36.572V338.667z M179.81,232h36.572v32H179.81V232z M179.809,285.333h36.572v32h-36.572 V285.333z"/> </g> </g> </g> </g>
                        </svg>
                        Annuel
                     </a>
                  </li>
            </ul>
         </li>

         <li>
            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
            <img src="{{asset('svg/exit.svg')}}" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white" aria-hidden="true" alt="">


               <span class="flex-1 ms-3 whitespace-nowrap">Exit</span>
            </a>
         </li>
      </ul>
   </div>
</div>
