
<!-- Main modal -->
<div id="authentication-modal1" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Ajouter {{$libele}}
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="authentication-modal1">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="{{route($action)}}" method="post" enctype="multipart/form-data">
                    @csrf 
                    @method('post')
                    <div>
                        <label for="cat" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Libele {{$libele}}</label>
                        <input type="text" name="libele" id="cat" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="" required />
                    </div>
                    <div class="mb-5">
                                                 
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Prendre une image</label>
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                            aria-describedby="file_input_help" 
                            id="file_input1" type="file" name="image" accept=".jpg, .jpeg, .png, .gif, .jfif" />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG ou GIF (MAX. 800x400px).</p>
                        <img id="imagePreview1" class="w-20 h-20 rounded" src="#" alt="Voir">
                    </div>
                    <div class="hidden">
                        <label for="pass" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your password</label>
                        <input type="password" name="user_id" id="pass" value="{{$user_id}}" placeholder="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"  />
                    </div>
                    

                    <div class="inline-flex items-center justify-center w-full">
                        <hr class="w-64 h-1 my-8 bg-gray-200 border-0 rounded dark:bg-gray-700">
                        <div class="absolute px-4 -translate-x-1/2 bg-white left-1/2 dark:bg-gray-900">
                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 14">
                                <path d="M6 0H2a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3H2a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Zm10 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h4v1a3 3 0 0 1-3 3h-1a1 1 0 0 0 0 2h1a5.006 5.006 0 0 0 5-5V2a2 2 0 0 0-2-2Z"/>
                            </svg>
                        </div>
                    </div>

                    <label for="" class="block mb-2 text-dm font-medium text-gray-900 dark:text-white">Ajouter Marque de produit</label>
                    
                    <div id="dynamicForm" class="mb-5">
                        <div class="input-container mb-5">
                            <label for="assertionInput1" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Marque
                            </label>
                            <input type="text" id="assertionInput1" class="dynamic-input block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                             placeholder="Saisir la marque" name="marque[]" required>
                        </div>
                    </div>
                   
                    

                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Créer {{$libele}}</button>
                    <!-- <div class=" hidden text-sm font-medium text-gray-500 dark:text-gray-300">
                        Not registered? <a href="#" class="text-blue-700 hover:underline dark:text-blue-500">Create account</a>
                    </div> -->
                </form>
            </div>
        </div>
    </div>
</div> 
<script>

    const form = document.getElementById('dynamicForm');
    form.addEventListener('input', function(event) {
        const inputs = document.querySelectorAll('.dynamic-input');
        const lastInput = inputs[inputs.length - 1];
        const num = inputs.length;

        // Vérifie si la valeur du dernier input n'est pas vide et s'il est le dernier input
        if (lastInput.value !== '' && lastInput !== inputs[inputs.length - 2]) {

            const newInputContainer = document.createElement('div');
            newInputContainer.className = "mb-5 input-container";

            const newInput = document.createElement('input');
            newInput.type = 'text';
            newInput.className = "block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500";
            newInput.classList.add('dynamic-input');
            newInput.placeholder = 'Saisir la marque';
            newInput.name = `marque[]`;

;
            newInputContainer.appendChild(newInput);
            form.appendChild(newInputContainer);
        }
    });

    const imageInput1 = document.getElementById('file_input1');
    const imagePreview1 = document.getElementById('imagePreview1');

    imageInput1.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                imagePreview1.setAttribute('src', event.target.result);
                imagePreview1.classList.remove('hidden'); // Affiche l'image
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview1.classList.add('hidden'); // Cache l'image si aucun fichier
        }
    });
</script>
