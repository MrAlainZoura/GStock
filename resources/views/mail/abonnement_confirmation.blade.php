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
  <h2>Confirmation paiement abonnement {{ $content['code'] }}</h2>
  <p class="">cliquer sur le lien ci-dessous </p>
  <a class="tb upcase" href="{{ $content["route"] }}">Activer ici</a> 
  ou copier le lien et coller dans un navigateur : {{  $content["route"] }}
    <h6>@coryright zouracorp 2026</h6>
</body>
</html>

@php
// die();
@endphp