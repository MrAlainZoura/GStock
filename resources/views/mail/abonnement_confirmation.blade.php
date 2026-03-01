<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation paiement</title>
    <style>
        body {
          font-family: Arial, sans-serif;
          margin: 20px;
        }
       
        .tc{
          text-align: center;
        }
        .df{
          display: flex;
        }
        .jc{
          justify-content: center;
        }
        .tb{
          font-weight: 700;
        }
        .upcase{
          text-transform: capitalize;
        }
    </style>
</head>
<body>

  <div>
    <p>Administrateur nom complet : {{ $content['code']->user->name}} {{ $content['code']->user->postnom}} {{ $content['code']->user->prenom}}</p>
    <p>Administrateur email : {{ $content['code']->user->email}}</p>
    <p>Administrateur téléphone : {{ $content['code']->user->tel}}</p>
  </div>
  <div>
    <p>
      Souscption {{ $content['code']->abonnement->name}} 
      pour une durée de {{ $content['code']->duree}} mois 
      montant à payer {{ $content['code']->montant}}$ (usd)
    </p>
  </div>
  <h2>Confirmation paiement abonnement {{ $content['code']->code }}</h2>
  <p class="">cliquer sur le lien ci-dessous </p>
  <a class="tb upcase" href="{{ $content["route"] }}">Activer ici</a> 
  ou copier le lien et coller dans un navigateur : {{  $content["route"] }}

  <br><br>
  <h2>Confirmation full time d'abonnement</h2>
  <p class="">cliquer sur le lien ci-dessous </p>
  <a class="tb upcase" href="{{ $content["routeFull"] }}">Activer full time ici</a> 
  ou copier le lien et coller dans un navigateur : {{  $content["routeFull"] }}

    <h6>@coryright zouracorp 2026</h6>
</body>
</html>

@php
// die();
@endphp