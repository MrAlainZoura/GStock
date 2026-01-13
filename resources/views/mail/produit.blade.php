<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport produit depot</title>
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
    @endphp
  <h2>Rapport {{ $rapport['periode'] }}  {{ $depotLibele }} </h2>
  
  @if ((int)$rapport['showVente'] > 0)
    <h2>Tableau de resumé de produit vendu {{ $depotLibele }}</h2>
    <table class="table-style">
      <thead>
        <tr class="header-row">
          <th>Categorie</th>
          <th>Marque</th>
          <th>Pièce Vendue</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($rapport['venteTri'] as $cat=>$allMarque)
              <tr>
                <td colspan="3" class="tb">{{ $cat}}</td>
              </tr>
              @php
                $index = 1;
                $total = 0;
              @endphp
              @foreach ($allMarque as $marque=>$qte )
              <tr>
                <td>{{ $index++ }}.</td>
                <td>{{ $marque }}</td>
                <td>{{ $qte }}</td>
              </tr>
                @php
                  $total+=(int)$qte;
                @endphp
              @endforeach
              <tr class="tb">
                <td colspan="2" >Total vente {{$cat}}</td>
                <td>{{$total}}</td>
              </tr>
          @endforeach        
      </tbody>
    </table>        
  @else 
    <h4>Aucune vente enregistrée dans cette intervalle</h4>
  @endif
  <h2>Tableau de resumé de stock produit {{ $depotLibele }}</h2>
  <table class="table-style">
    <thead>
      <tr class="header-row">
        <th>#. Libele</th>
        <th>Categorie</th>
        <th>Entrée</th>
        <th>Transf</th>
        <th>Vendu</th>
        <th>Reste</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($rapport['resumeProduit'] as $k=>$v)
            <tr>
              <td>{{ $k+1 }}. {{$v['libele']}}</td>
              <td>{{$v['cat']}}</td>
              <td> {{$v['enter']}}</td>
              <td>{{$v['trans']}}</td>
              <td>{{$v['vente']}}</td>
              <td>{{$v['rest']}}</td>
            </tr>
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