@extends('base')
@section('title', "Catégorie de produit")

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
    <div class="flex flex-wrap justify-center items-center gap-5 p-4 sm:items-center     ">
        @foreach($cat as $key=>$v)
            @include('composant.cat', ['id'=>$v->id,'image'=>"$v->image" ,'libele'=>"$v->libele",'marque'=>count($v->marque),'tab'=>$v->marque])
        @endforeach   
    </div>

    <section >        
        @include('composant.modal_cat',['libele'=>'Catégorie de produit', 'action'=>"cat-pro.store",'user_id'=> $user->id ]) 
    </section>
    @include('composant.sidebar',['depot'=> session('depot'), 'depot_id'=> session('depot_id')])

@endsection


@section('footer')
    @include('composant.footer')
@endsection

