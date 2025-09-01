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
                    $recette +=(float) $vP->prixT;
                    $recetteFc +=(float)$vP->prixT * (float)$vV->updateTaux;
                @endphp
              @formaMille((float)$vV->updateTaux) Fc
              </td>
              <td>
                @formaMille((float)$vP->prixT) {{ $vV->devise->libele }}
                <!-- {{$vV->updateTaux}}Fc -->
              </td>
              <td>
                @if($vV->user != null)
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
        @if ($rapport ['compassassion']->count() > 0)
        <tr>
          <td colspan="6" style="font-weight: bold; font-size: 18px;">Compassassion / Contre valeur</td>
        </tr>
        @foreach ($rapport ['compassassion'] as $vcomp)
        <tr>
                <td>
                  {{$vcomp->code}} <br>
                  {{$vcomp->created_at}}
                </td>
                <td colspan="2">
                  <ol>
                    @foreach ($vcomp->compassassion as $kc=>$vc)
                     <li>
                      {{ $vc->produit->libele }} {{ $vc->quantite }}pc <span>→</span> {{ $vc->prixT }} {{ $vcomp->devise->libele }}
                    </li> 
                    @endforeach

                  </ol> 
                  <p style="font-weight: bold;">Contre (ancien article)</p>
                  <ol>
                    @foreach ($vcomp->venteProduit as $cvp)
                     <li>
                      {{ $cvp->produit->libele}} {{ $cvp->quantite}}pc <span>→</span> {{ $cvp->prixT }} {{ $vcomp->devise->libele }}
                     </li> 
                    @endforeach
                </td>
                
                <td>
                @formaMille((float)$vcomp->updateTaux) Fc
                </td>
                <td>
                   @php
                      $ajout = (float) $vcomp->paiement->sortByDesc('created_at')->first()->net - (float)$vcomp->paiement->sortBy('created_at')->first()->net;
                      $recette +=(float) $ajout;
                      $recetteFc +=(float) $ajout * (float) $vcomp->updateTaux;
                    @endphp
                   {{ $vcomp->paiement->sortBy('created_at')->first()->net}} + {{ $ajout }}<br>
                   <br>{{ $vcomp->paiement->sortByDesc('created_at')->first()->net }} {{ $vcomp->devise->libele }}     
                </td>
                <td>
                  @if($vcomp->user != null)
                      {{$vcomp->user->name ." ".$vcomp->user->prenom}}
                  @else
                      Utilisateur Suspendu
                  @endif
                  <br>
                  # shop
                </td>
              </tr>
        @endforeach
        @endif       
    </tbody>
    <tfoot>
        <tr class="footer-row">
            <td colspan="2"><strong>Total</strong></td>
            <td colspan="2">
                @formaMille( (float)$recetteFc) Fc
            </td>
              <td colspan="3">
                @if (count($rapport['vente']) >0)
                    @foreach ($vV->depot->devise as $cle=>$dev )
                        @formaMille( (float)$recetteFc/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
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