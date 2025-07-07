<div class="grid grid-cols-3">
    @if(Auth::user()->user_role->role->libele=='user')
        <a href="{{ route($seeRoute, $seeParam) }}" title="Voir plus">
            <svg class="w-[26px] h-[26px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
        </a> 
        @if (Route::current()->getName()=="user.index")
            <a href="{{route($editeRoute, $editParam)}}" title="Editer">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                </svg>
            </a>
         @endif
         @if (Route::current()->getName()=="aproDepot")
        <form action="{{route($deleteRoute, $deleteParam)}}" method="post">
            <!-- confirmation de l'approvisionnement -->
            @csrf
            @method('post')
            <button type="submit" title="Confirmer approvisionnement" itemName ="{{$itemName}}">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.009 512.009" xml:space="preserve" width="24px" height="24px" fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                <g id="SVGRepo_iconCarrier"> <path style="fill:#E2E2E2;" d="M256,512.004c141.392,0,256-114.608,256-256s-114.608-256-256-256S0,114.612,0,256.004 S114.608,512.004,256,512.004z"/> <polygon style="fill:#FFFFFF;" points="208.832,155.668 190.304,176.548 273.984,250.9 417.712,66.82 395.712,49.636 270.304,210.244 "/> <path style="fill:#1ded35;" d="M219.456,143.716l-11.968-10.624l-10.624,11.968l-18.528,20.88l-10.624,11.968l11.952,10.624 l83.68,74.336l12.752,11.328l10.496-13.44l143.712-184.08l9.84-12.608l-12.608-9.84l-22-17.184l-12.608-9.856l-9.856,12.608 L268.16,186.98L219.456,143.716z"/> <path d="M453.904,364.324c-34.8,17.328-146.752,52-199.712,0c0,0,122.56,10.672,127.088,0c4.544-10.672-13.616-28-108.928-48 s-163.408,0-163.408,0v128c0,0,60.512-30.672,90.784-16s113.472,36,163.408,32s130.112-68,145.248-89.328 C523.488,349.652,488.688,346.98,453.904,364.324z"/> <rect y="316.324" style="fill:#1ded35;" width="72.624" height="144"/> </g>
            </svg>
            </button>
        </form>
        @endif
        
    @elseif(Auth::user()->user_role->role->libele =='Administrateur' || Auth::user()->user_role->role->libele=='Super admin')
        <a href="{{ route($seeRoute, $seeParam) }}" title="Voir plus">
            <svg class="w-[26px] h-[26px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z"/>
                <path stroke="currentColor" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
            </svg>
        </a>
    
        <a href="{{route($editeRoute, $editParam)}}" title="Editer">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
        </svg>
        </a>

        @if(Route::current()->getName()=="aproDepot")
        <form action="{{route($deleteRoute, $deleteParam)}}" method="post">
            <!-- confirmation de l'approvisionnement -->
            @csrf
            @method('post')
            <button type="submit" title="Confirmer approvisionnement" itemName ="{{$itemName}}">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.009 512.009" xml:space="preserve" width="24px" height="24px" fill="#000000">
                <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                <g id="SVGRepo_iconCarrier"> <path style="fill:#E2E2E2;" d="M256,512.004c141.392,0,256-114.608,256-256s-114.608-256-256-256S0,114.612,0,256.004 S114.608,512.004,256,512.004z"/> <polygon style="fill:#FFFFFF;" points="208.832,155.668 190.304,176.548 273.984,250.9 417.712,66.82 395.712,49.636 270.304,210.244 "/> <path style="fill:#1ded35;" d="M219.456,143.716l-11.968-10.624l-10.624,11.968l-18.528,20.88l-10.624,11.968l11.952,10.624 l83.68,74.336l12.752,11.328l10.496-13.44l143.712-184.08l9.84-12.608l-12.608-9.84l-22-17.184l-12.608-9.856l-9.856,12.608 L268.16,186.98L219.456,143.716z"/> <path d="M453.904,364.324c-34.8,17.328-146.752,52-199.712,0c0,0,122.56,10.672,127.088,0c4.544-10.672-13.616-28-108.928-48 s-163.408,0-163.408,0v128c0,0,60.512-30.672,90.784-16s113.472,36,163.408,32s130.112-68,145.248-89.328 C523.488,349.652,488.688,346.98,453.904,364.324z"/> <rect y="316.324" style="fill:#1ded35;" width="72.624" height="144"/> </g>
            </svg>
            </button>
        </form>
        @else
            <a title="Supprimer" itemName ="{{$itemName}}" id="linkDelete" href="{{route($deleteRoute, $deleteParam)}}" data-modal-target="popup-modal" data-modal-toggle="popup-modal">
                <svg class="w-[26px] h-[26px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 9-6 6m0-6 6 6m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </a>
        @endif
    @endif      
</div>
