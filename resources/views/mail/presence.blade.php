<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport présence depot</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 20px;
        }

        .table-style {
        width: 100%;
        border-collapse: collapse;
        }

        .table-style th,
        .table-style td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: left;
        }

        .header-row {
        background-color: #007BFF;
        color: white;
        }
        .footer-row {
        background-color: #f1f1f1;
        font-weight: bold;
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
    @php
      $depotLibele = $rapport['depot']->libele;
       $numerotation = 1;
    @endphp
  <h2>Rapport {{ $rapport['periode'] }}  {{ $depotLibele }} </h2>
    <h2>Tableau de Présence {{ $rapport['periode'] }} {{ $depotLibele }}</h2>
  @if (str_contains($rapport['periode'], "Journalier") == false)
    <h3>Statistique globale</h3> 
    <table class="table-style">
      <thead>
        <tr class="header-row">
          <th>N°</th>
          <th>Nom complet</th>
          <th>Au Bureau</th>
          <th>A l'Extérieur</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($rapport['stats'] as $kcle=> $stat)
            <tr>
              <td> {{$numerotation++}}</td>
              <td>{{$stat['user'][0]->user->name}} {{$stat['user'][0]->user->postnom}} {{$stat['user'][0]->user->prenom}}</td>
              <td>{{ $stat['confirmed_true'] }}</td>
              <td>{{ $stat['confirmed_false'] }}</td>
            </tr>
          @endforeach        
      </tbody>
    </table> 
  @endif
  <h3> {{  (str_contains($rapport['periode'], "Journalier") == false)? "Statistiques détailées":""}}</h3>
  <table class="table-style">
    <thead>
      <tr class="header-row">
        <th>N°</th>
        <th>Nom complet</th>
        <th>Arrivée</th>
        <th>Départ</th>
        <th>Bureau</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($rapport['presence'] as $jour=>$presence)
        <tr>
          <td colspan="5" class="upcase tb lc">{{ $jour }}</td>  
        </tr>
          @foreach ($presence as $clef=>$detail) 
          <tr>
            <td> {{$clef+1}}</td>
            <td class="upcase"> {{$detail->user->name}} {{$detail->user->postnom}} {{$detail->user->prenom}}</td>
            <td> @heure( $detail->created_at)</td>
            <td> 
            @if($detail->updated_at != $detail->created_at) 
              - <!-- @heure($detail->updated_at) -->
            @else 
              -
            @endif
            </td>
            <td> {{ ($detail->confirm) ? "Oui" : "Ailleurs"}}           </td>
          </tr>
          @endforeach
        @endforeach        
    </tbody>
  </table> 
    <!-- <h6>Pas de panique c'est zoura, je teste mon application</h6> -->
    <h6>@coryright zouracorp 2025</h6>
</body>
</html>

@php
// die();
@endphp