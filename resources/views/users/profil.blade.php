@extends('base')
@section('title', "Edite user")

@section('header')
  @include('composant.hearder', ['user_email'=>auth()->user()->email, 'user_name'=>auth()->user()->name])
@endsection

@section('main')  

    @if(session('success'))
    <div class="alert-success">
    @include('composant.alert_suc', ['message'=>session('success')])
    </div>
    @endif
    @if(session('echec'))
    <div class="alert-echec">
        @include('composant.alert_echec', ['message'=>session('echec')])
    </div>
    @endif
    

    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
            @if (Auth::user()->id == $user->id || Auth::user()->user_role->role->libele=='Administrateur' || Auth::user()->user_role->role->libele=='Super admin')
            <div class="flex justify-between">
                <h2 class= "mb-4 text-xl font-bold text-gray-900 dark:text-white">Editer mon profil {{$user->name." ".$user->postnom." ".$user->prenom}}</h2>
                <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline">
                Changer mot de passe 
                    <!-- <svg width="24px" height="24px" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <path d="M11 25C11 22.2386 13.2386 20 16 20H48C50.7614 20 53 22.2386 53 25V37C53 48.598 43.598 58 32 58C20.402 58 11 48.598 11 37V25Z" fill="#0438f6"/> <path fill-rule="evenodd" clip-rule="evenodd" d="M16.4192 51.0799L24.0187 20H28.0187L19.6997 54.0225C18.5055 53.1581 17.4058 52.1711 16.4192 51.0799ZM23.1985 56.0721C22.591 55.7913 21.9992 55.4823 21.4248 55.1468L30.0187 20H32.0187L23.1985 56.0721Z" fill="white"/> <path d="M42 16C42 13.3478 40.9464 10.8043 39.0711 8.92893C37.1957 7.05357 34.6522 6 32 6C29.3478 6 26.8043 7.05357 24.9289 8.92893C23.0536 10.8043 22 13.3478 22 16" stroke="#0438f6" stroke-width="4" stroke-linecap="round"/> <path d="M18 32H50C53.3137 32 56 34.6863 56 38C56 41.3137 53.3137 44 50 44H18V32Z" fill="#ccd6e1"/> <circle cx="18" cy="38" r="10" fill="#ccd6e1"/> <circle cx="18" cy="34" r="2" fill="#0438f6"/> <circle cx="34" cy="38" r="2" fill="#0438f6"/> <circle cx="41" cy="38" r="2" fill="#0438f6"/> <circle cx="48" cy="38" r="2" fill="#0438f6"/> <path d="M21.0365 43C22.1209 43 23.0344 42.0884 22.6194 41.0866C22.3681 40.48 21.9998 39.9288 21.5355 39.4645C21.0712 39.0002 20.52 38.6319 19.9134 38.3806C19.3068 38.1293 18.6566 38 18 38C17.3434 38 16.6932 38.1293 16.0866 38.3806C15.48 38.6319 14.9288 39.0002 14.4645 39.4645C14.0002 39.9288 13.6319 40.48 13.3806 41.0866C12.9656 42.0884 13.8791 43 14.9635 43L21.0365 43Z" fill="#0438f6"/> </g>
                    </svg> -->
                    <!-- <svg class="ml-1" fill="#1201f9" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.00 512.00" xml:space="preserve" transform="rotate(0)matrix(1, 0, 0, 1, 0, 0)" stroke="#1201f9" stroke-width="1.024">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M371.004,141.833L241.783,12.621c-8.055-8.055-18.773-12.493-30.165-12.493c-11.401,0-22.11,4.437-30.165,12.493 L12.501,181.572C4.437,189.628,0,200.337,0,211.738s4.437,22.11,12.501,30.174l90.103,90.112 c-0.119,5.734-0.205,11.605-0.205,17.715c0,36.011,0,85.333,34.133,85.333c28.006,0,34.133-21.751,34.133-30.131 c0-4.668-3.746-8.405-8.414-8.474c-4.446-0.213-8.516,3.686-8.653,8.354c-0.077,3.098-1.57,13.184-17.067,13.184 c-17.067,0-17.067-41.088-17.067-68.267c0-157.884,44.331-179.157,45.594-179.721c4.326-1.57,6.639-6.315,5.171-10.718 c-1.493-4.463-6.315-6.903-10.795-5.393c-2.236,0.742-11.093,4.454-21.188,18.321c-1.067-3.106-1.715-6.545-1.715-10.223 c0-14.123,11.486-25.6,25.6-25.6c14.123,0,25.6,11.477,25.6,25.6c0,11.733-7.927,21.939-19.285,24.815 c-4.565,1.161-7.33,5.803-6.17,10.368c0.973,3.866,4.446,6.434,8.26,6.434c0.691,0,1.399-0.085,2.099-0.265 c18.944-4.796,32.162-21.803,32.162-41.353c0-23.526-19.14-42.667-42.667-42.667c-23.518,0-42.667,19.14-42.667,42.667 c0,10.385,3.2,19.883,8.747,27.196c-11.016,22.656-21.419,59.605-24.713,119.578l-78.933-78.942 c-4.838-4.83-7.501-11.255-7.501-18.099c0-6.835,2.662-13.261,7.501-18.099L193.519,24.687c9.668-9.677,26.53-9.668,36.198,0 l129.22,129.212c9.975,9.984,9.975,26.223,0,36.207L189.986,359.057c-9.668,9.66-26.539,9.668-36.207,0l-4.412-4.42 c-3.337-3.328-8.738-3.328-12.066,0c-3.337,3.336-3.337,8.738,0,12.075l4.412,4.412c8.055,8.055,18.773,12.493,30.165,12.493 c11.401,0,22.11-4.437,30.174-12.493l168.951-168.951C387.635,185.54,387.635,158.464,371.004,141.833z"/> <path d="M309.7,304.572l153.6,153.6c1.673,1.664,3.857,2.5,6.033,2.5c2.185,0,4.369-0.836,6.033-2.5 c3.336-3.337,3.336-8.738,0-12.066l-153.6-153.6c-3.328-3.337-8.73-3.337-12.066,0 C306.372,295.834,306.372,301.235,309.7,304.572z"/> <path d="M509.5,411.972l-153.6-153.6c-3.328-3.336-8.73-3.336-12.066,0c-3.328,3.328-3.328,8.73,0,12.066l151.1,151.1v73.267 h-68.267v-25.6c0-4.71-3.814-8.533-8.533-8.533h-25.6v-25.6c0-4.71-3.814-8.533-8.533-8.533h-25.6v-25.6 c0-4.71-3.814-8.533-8.533-8.533h-30.592l-48.708-48.7c-3.328-3.337-8.73-3.337-12.066,0c-3.328,3.328-3.328,8.73,0,12.066 l51.2,51.2c1.604,1.596,3.772,2.5,6.033,2.5h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533 h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533h85.333c4.719,0,8.533-3.823,8.533-8.533v-85.333 C512,415.744,511.104,413.568,509.5,411.972z"/> </g> </g> </g> </g>
                    </svg> -->
                    <svg fill="#1201f9" height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512.00 512.00" xml:space="preserve" transform="rotate(45)matrix(-1, 0, 0, -1, 0, 0)" stroke="#1201f9" stroke-width="1.024">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"/>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"/>
                        <g id="SVGRepo_iconCarrier"> <g> <g> <g> <path d="M371.004,141.833L241.783,12.621c-8.055-8.055-18.773-12.493-30.165-12.493c-11.401,0-22.11,4.437-30.165,12.493 L12.501,181.572C4.437,189.628,0,200.337,0,211.738s4.437,22.11,12.501,30.174l90.103,90.112 c-0.119,5.734-0.205,11.605-0.205,17.715c0,36.011,0,85.333,34.133,85.333c28.006,0,34.133-21.751,34.133-30.131 c0-4.668-3.746-8.405-8.414-8.474c-4.446-0.213-8.516,3.686-8.653,8.354c-0.077,3.098-1.57,13.184-17.067,13.184 c-17.067,0-17.067-41.088-17.067-68.267c0-157.884,44.331-179.157,45.594-179.721c4.326-1.57,6.639-6.315,5.171-10.718 c-1.493-4.463-6.315-6.903-10.795-5.393c-2.236,0.742-11.093,4.454-21.188,18.321c-1.067-3.106-1.715-6.545-1.715-10.223 c0-14.123,11.486-25.6,25.6-25.6c14.123,0,25.6,11.477,25.6,25.6c0,11.733-7.927,21.939-19.285,24.815 c-4.565,1.161-7.33,5.803-6.17,10.368c0.973,3.866,4.446,6.434,8.26,6.434c0.691,0,1.399-0.085,2.099-0.265 c18.944-4.796,32.162-21.803,32.162-41.353c0-23.526-19.14-42.667-42.667-42.667c-23.518,0-42.667,19.14-42.667,42.667 c0,10.385,3.2,19.883,8.747,27.196c-11.016,22.656-21.419,59.605-24.713,119.578l-78.933-78.942 c-4.838-4.83-7.501-11.255-7.501-18.099c0-6.835,2.662-13.261,7.501-18.099L193.519,24.687c9.668-9.677,26.53-9.668,36.198,0 l129.22,129.212c9.975,9.984,9.975,26.223,0,36.207L189.986,359.057c-9.668,9.66-26.539,9.668-36.207,0l-4.412-4.42 c-3.337-3.328-8.738-3.328-12.066,0c-3.337,3.336-3.337,8.738,0,12.075l4.412,4.412c8.055,8.055,18.773,12.493,30.165,12.493 c11.401,0,22.11-4.437,30.174-12.493l168.951-168.951C387.635,185.54,387.635,158.464,371.004,141.833z"/> <path d="M309.7,304.572l153.6,153.6c1.673,1.664,3.857,2.5,6.033,2.5c2.185,0,4.369-0.836,6.033-2.5 c3.336-3.337,3.336-8.738,0-12.066l-153.6-153.6c-3.328-3.337-8.73-3.337-12.066,0 C306.372,295.834,306.372,301.235,309.7,304.572z"/> <path d="M509.5,411.972l-153.6-153.6c-3.328-3.336-8.73-3.336-12.066,0c-3.328,3.328-3.328,8.73,0,12.066l151.1,151.1v73.267 h-68.267v-25.6c0-4.71-3.814-8.533-8.533-8.533h-25.6v-25.6c0-4.71-3.814-8.533-8.533-8.533h-25.6v-25.6 c0-4.71-3.814-8.533-8.533-8.533h-30.592l-48.708-48.7c-3.328-3.337-8.73-3.337-12.066,0c-3.328,3.328-3.328,8.73,0,12.066 l51.2,51.2c1.604,1.596,3.772,2.5,6.033,2.5h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533 h25.6v25.6c0,4.71,3.823,8.533,8.533,8.533h85.333c4.719,0,8.533-3.823,8.533-8.533v-85.333 C512,415.744,511.104,413.568,509.5,411.972z"/> </g> </g> </g> </g>
                    </svg>
                </button>
            </div>
            @else
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Editer le profil de {{$user->name." ".$user->postnom." ".$user->prenom}}</h2>
            @endif
      
            <form action="{{route('user.update', parameters: $user->id*652062511003)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="w-full flex flex-col sm:flex-row sm:col-span-2 sm:block"> 
                        <img id="imagePreview" class="w-20 h-20 rounded-full m-2 border border-gray-300 " 
                            @if ($user->image!=null)
                                src="{{asset('storage/users/'.$user->image)}}"
                            @else
                                src="{{asset('svg/man.svg')}}"
                            @endif
                        alt="Voir"> 
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Prendre une image</label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                                aria-describedby="file_input_help" 
                                id="file_input" type="file" name="image" accept=".jpg, .jpeg, .png, .gif, .jfif" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG ou GIF (MAX. 800x400px).</p>
                        </div>                           
                    </div>
                    <div class="w-full">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nom</label>
                        <input type="text" name="name" value="{{$user->name}}" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="nom" required="">
                    </div>
                    <div class="w-full">     
                        <label for="postnom" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Postnom</label>
                        <input type="text" value="{{$user->postnom}}" name="postnom" id="postnom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="postnom" >
                    </div>
                    <div class="w-full">
                        <label for="prenom" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Prenom</label>
                        <input type="text" name="prenom" value="{{$user->prenom}}" id="prenom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Christ" >
                    </div>
                    <div>
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                        <select id="category" name="genre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected value="{{$user->genre}}">{{$user->genre}}</option>
                            <option value="F">F</option>
                            <option value="M">M</option>
                        </select>
                    </div>
                    <div>
                        <label for="" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date de naissance</label>
                        <div class="flex max-w-sm">
                            <label for="datepicker-actions" class="cursor-pointer inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </label>
                            <input name="naissance" value="{{$user->naissance}}" id="datepicker-actions" datepicker datepicker-buttons datepicker-autoselect-today type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-none rounded-e-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="MM / JJ / AAAA">
                        </div>
                    </div> 
                    <div class="w-full">
                        <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input type="email" value="{{$user->email}}" name="email" id="brand" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="email@gstock.cd" required="">
                    </div>
                    <div class="w-full">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fonction</label>
                        <input type="text" value="{{$user->fonction}}" name="fonction" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Livreur" required="">
                    </div>
                    <div>
                        <label for="shop" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Affectation</label>
                        <select name="depot_id" id="shop" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @if(count($user->depotUser)>0)
                                <option selected value="{{$user->depotUser[0]->depot->id}}">
                                    {{$user->depotUser[0]->depot->libele}} 
                                </option>
                            @else
                            <option selected value="">
                                    Choisir une affectation 
                                </option>
                            @endif
                                
                            @foreach ($depot as $itm )
                                <option value="{{$itm->id}}">{{$itm->libele}}</option>
                            @endforeach  
                        </select>
                    </div>
                    <div class="w-full">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Niveau d'étude</label>
                        <input type="text" value="{{$user->niveauEtude}}" name="niveauEtude"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="D6, G3, L2, Master" >
                    </div>
                    <div class="w-full">
                        <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Option</label>
                        <input type="text" name="option" value="{{$user->option}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Informatique,..." >
                    </div>
                    <div class="w-full">
                        <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Téléphone</label>
                        <input type="text" name="tel" value="{{$user->tel}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="+243 ..." >
                    </div>
                    <div class="w-full">
                        <label  class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Adresse</label>
                        <input type="text" name="adresse" value="{{$user->adresse}}"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Kin, Lemba, Salongo" required="">
                    </div>
                </div>
          
                 <div class="flex justify-center m-5">
                    <button type="submit"  class="btn-primary w-1/2 px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Mettre à jour</button>
                </div>
            </form>
        </div>

        <!-- Main modal changer mot de passe -->
        <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Changement de mot passe
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <form class="space-y-4" action="{{route("updataPass",$user->id*652062511003)}}" method="post">
                            @csrf
                            @method('put')
                            <div>
                                <label for="holdPass" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mot de passe actuel</label>
                                <input type="password" name="holdPass" id="holdPass" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="mot de passe actuel" required />
                            </div>
                            <div>
                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                            </div>
                            <div>
                                <label for="ConfirmPassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nouveau mot de passe</label>
                                <input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                            </div>
                            
                            <button type="submit" id="submit" class="hidden w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Mettre à jour le mot de passe</button>
                            <label id="passwordTrue" class="text-red-600 p-2">Les mots de passe doivent correspondre</label>
                        </form>
                        
                        <form action="{{route('resetPass',$user->id*652062511003)}}" method="post" class="text-sm font-medium text-gray-500 dark:text-gray-300"> 
                            @csrf
                            @method('put')
                            Reinitialiser mon 
                            <button type="submit" class="text-blue-700 hover:underline dark:text-blue-500">
                                mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div> 

    </section>

        @include('composant.sidebar',['depot'=>session('depot')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
  
    const imageInput = document.getElementById('file_input');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview.setAttribute('src', event.target.result);
                imagePreview.classList.remove('hidden'); // Affiche l'image
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden'); // Cache l'image si aucun fichier
        }
    });

    const password=document.getElementById('password');
    const ConfirmPassword=document.getElementById('ConfirmPassword');
    const submit = document.getElementById('submit');
    const passwordTrue = document.getElementById('passwordTrue');

    ConfirmPassword.addEventListener('input', function() {
        if(ConfirmPassword.value != password.value){
            passwordTrue.classList.remove ("hidden");
            submit.classList.add("hidden")
        }else{
            submit.classList.remove ("hidden");
            passwordTrue.classList.add("hidden")
        }
    });
});
</script>