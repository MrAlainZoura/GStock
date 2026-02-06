@extends('base')
@section('title', "Liste de vente")

@section('header')
  @include('composant.hearder', ['user_email'=>Auth::user()->email, 'user_name'=>Auth::user()->name])
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
    

    <section class="p-10 gap-5 w-full">
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Liste de Vente de {{$depot->libele}} </h1>
        </div>
        <div class="w-full flex justify-end m-5 gap-5">
            <div class="">
                <select required name="" id="filtre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                     <option selected value="1"> {{ $tranche ? $tranche.' mois' : "Filtre mois" }}</option>
                        @for($i=1; $i<19; $i++ )
                            <option value="{{$i}}">
                                {{ $i }} mois
                            </option>
                        @endfor
                </select>
            </div>
            <a href="{{route('rapportDownload',['depot'=>$depot->id*12, 'periode'=>'today'])}}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                PDF du jour
                <img src="{{asset('svg/pdf.svg')}}" class="w-6 rounded" alt="">
            </a>
        </div>
        @include('composant.dataTableReservation',["data"=>$reservation, 'deviseList'=>$depot->devise])
    </section>
    

    @include('composant.sidebar',['depot'=> $depot->libele, 'depot_id'=>$depot->id])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const selectFilte =window.document.getElementById("filtre");
        if(selectFilte){
                selectFilte.addEventListener('change', ()=>{
                    let url = @json(route('reservation.list',$depot->id*13));
                        url =`${url}/${selectFilte.value}`
                    // console.log(url);
                    link(url);
                });
            }

        });
    const link = (url)=>{
            if (!url || typeof url !== 'string') {
                console.log('URL invalide.');
                return;
            }
            const openLink = window.open(url, '_self');
        }
</script>