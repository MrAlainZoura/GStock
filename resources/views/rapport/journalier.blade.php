@extends('base')
@section('title', "Rapport journalier ". session('depot'))

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
    <!-- <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl dark:text-white">Team management</h2> -->

    <section class="p-10 gap-5 w-full">
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-2xl font-extrabold tracking-tight leading-none text-gray-900 md:text-3xl lg:text-4xl dark:text-white">Rapport journalier  {{session('depot')}} <span id="today"></span> </h1>
        </div>
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">1. VENTE </h1>
        </div>
        
        @include('composant.rapportTable',['data'=> $venteJour,"period"=>"Recette du jour"])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">2. APPROVISIONNEMENT </h1>
        </div>
        @include('composant.rapportTableAppro',['data'=> $approJour])
        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. TRANSFERT </h1>
        </div>
        @include('composant.rapportTableTrans',['data'=> $transJour])

        <div class="py-4 px-2 mx-auto max-w-screen-xl text-left lg:py-4">
            <h1 class="mb-4 text-xl  tracking-tight leading-none text-gray-900 md:text-xl lg:text-xl dark:text-white">3. RESUME STOCK </h1>
        </div>
            @include('composant.rapportTableResume',['data'=> $prodArrayResume])
    </section>

    @include('composant.sidebar',['depot'=> session('depot'), 'depot_id'=> session('depot_id')])
    @endsection


@section('footer')
    @include('composant.footer')
@endsection

<script>
document.addEventListener("DOMContentLoaded", () => {
    today();
})
    const today = ()=>{
    const dateTimeElement = document.getElementById('today');

    function updateDateTime() {
    const currentDateTime = new Date();

    const options = { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };

        const formattedDateTime = new Intl.DateTimeFormat('fr-FR', options).format(currentDateTime);

        const capitalizedDateTime = formattedDateTime.replace(/\b\w/g, (match) => match.toUpperCase());
        dateTimeElement.textContent = capitalizedDateTime;
    }

    setInterval(updateDateTime, 1000);
}
</script>