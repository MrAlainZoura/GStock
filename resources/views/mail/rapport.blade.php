<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport depot</title>
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

    </style>
</head>
<body>
    @php
        $recette =0;
        $recetteFc = 0;
        $depotLiebele ="";
        if (count( $rapport['vendeurs']) >0) {
          $depotLiebele = $rapport['vendeurs'][0]->depot->libele;
        }
    
    @endphp
  <h2>Tableau de vente {{ $depotLiebele }} </h2>
  <table class="table-style">
    <thead>
      <tr class="header-row">
        <th>Code</th>
        <th>Produit</th>
        <th>Quantité</th>
        <th>Taux vente</th>
        <th>Montant</th>
        <!-- <th>Lieu</th> -->
        <th>Vendeur</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($rapport['vente'] as $kV=>$vV)
            @foreach ($vV->venteProduit as $kP=>$vP )
            <tr>
              <td>
                {{$vP->vente->code}} <br>
                {{$vP->vente->created_at}}
              </td>
              <td>
                {{$vP->produit->marque->categorie->libele }} {{$vP->produit->marque->libele }} {{$vP->produit->libele }} 
              </td>
              <td>
                {{ $vP->quantite }} 
              </td>
              <td>
                @php
                    $recette +=$vP->prixT;
                    $recetteFc += $vP->prixT * $vV->updateTaux;
                @endphp
              @formaMille($vV->updateTaux) Fc
              </td>
              <td>
                @formaMille($vP->prixT) {{ $vV->devise->libele }}
                <!-- {{$vV->updateTaux}}Fc -->
              </td>
              <td>
                @if($vV->user_id != null)
                    {{$vV->user->name ." ".$vV->user->prenom}}
                @else
                    Utilisateur Suspendu
                @endif
                <br>
                # {{ $vV->type }}
              </td>
            </tr>
            @endforeach
        @endforeach        
    </tbody>
    <tfoot>
        <tr class="footer-row">
            <td colspan="2"><strong>Total</strong></td>
            <td colspan="2">
                @formaMille( $recetteFc) Fc
            </td>
              <td colspan="3">
                @if (count($rapport['vente']) >0)
                    @foreach ($vV->depot->devise as $cle=>$dev )
                        @formaMille( $recetteFc/$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                    @endforeach
                @endif
              </td>
            <!-- <td colspan="2">—</td> -->
        </tr>
    </tfoot>
  </table>        
  <h2>Tableau Classement Vendeur du mois {{ $depotLiebele }}</h2>

  <table class="table-style">
    <thead>
      <tr class="header-row">
        <th>N°</th>
        <th>Nom complet</th>
        <th>Vente</th>
      </tr>
    </thead>
    <tbody>
        @foreach ($rapport['vendeurs'] as $kl=>$vendeur)
            <tr>
              <td>{{$kl+1}}</td>
              <td>{{$vendeur->user->name}} {{$vendeur->user->postnom}} {{$vendeur->user->prenom}} </td>
              <td> {{$vendeur->count}}</td>
            </tr>
        @endforeach        
    </tbody>
   
  </table> 
  <h2>Tableau de resumé de stock produit {{ $depotLiebele }}</h2>
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