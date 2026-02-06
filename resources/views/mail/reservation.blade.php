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
        $recette =0;
        $recetteFc = 0;
        $recettePT = 0;
        $recetteDT = 0;
        $restePaiementTrancheFc=0;
        $restePaiementTrancheFcPT=0;
        $restePaiementTrancheFcDT=0;
        $depotLibele = $rapport['depot']->libele;
    @endphp
  <h2>Rapport {{ $rapport['periode'] }}  {{ $depotLibele }} </h2>
  <h2>Tableau de vente {{ $depotLibele }} </h2>
  <table class="table-style">
    <thead>
      <tr class="header-row">
        <th>Code</th>
        <th>Produit</th>
        <th>Durée</th>
        <th>Taux vente</th>
        <th>Montant</th>
        <!-- <th>Lieu</th> -->
        <th>Agent</th>
      </tr>
    </thead>
    <tbody>
      @if (is_array($rapport['vente']) && array_key_exists('avant', $rapport['vente']))
          @foreach ($rapport['vente']['avant'] as $kV=>$vV)
          @php
            $restePaiementTrancheFcPT += (float)$vV->paiement->sortByDesc('created_at')->first()->solde * (float)$vV->updateTaux;
          @endphp
            @foreach ($vV->reservationProduit as $kP=>$vP )
              <tr>
                <td>
                  {{$vP->reservation->code}} <br>
                  {{$vP->reservation->created_at}}
                </td>
                <td>
                  {{$vP->produit->marque->categorie->libele }} {{$vP->produit->marque->libele }} {{$vP->produit->libele }} 
                </td>
                <td>
                  {{ $vP->duree }} 
                </td>
                <td>
                  @php
                      $recettePT +=(float)$vP->montant * (float)$vV->updateTaux;
                  @endphp
                @formaMille((float)$vV->updateTaux) Fc
                </td>
                <td>
                  @formaMille((float)$vP->montant) {{ $vV->devise->libele }}
                  <!-- {{$vV->updateTaux}}Fc -->
                </td>
                <td>
                  @if($vV->user != null)
                      {{$vV->user->name ." ".$vV->user->prenom}}
                  @else
                      Utilisateur Suspendu
                  @endif
                                    
                </td>
              </tr>
            @endforeach
        @endforeach 
        <!-- debut -->
         
         <!-- fin -->
        <tr>
          <td colspan="2" class="tb">Sous total à 17h30</td>
          <td colspan="2" class="tb">
            @php
                $recettePT = (float) $recettePT - (float)$restePaiementTrancheFcPT;
                $restePaiementTrancheFc +=(float)$restePaiementTrancheFcPT;
            @endphp
             @formaMille((float) $recettePT ) Fc
          </td>
          <td colspan="2" class="tb">
              @foreach ($rapport['depot']->devise as $cle=>$dev )
                   @formaMille( (float)$recettePT/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                @endforeach
          </td>
        </tr>
        @foreach ($rapport['vente']['apres'] as $kV=>$vV)
        
          @php
            $restePaiementTrancheFcDT += (float)$vV->paiement->sortByDesc('created_at')->first()->solde * (float)$vV->updateTaux;
          @endphp
            @foreach ($vV->reservationProduit as $kP=>$vP )
              <tr>
                <td>
                  {{$vP->reservation->code}} <br>
                  {{$vP->reservation->created_at}}
                </td>
                <td>
                  {{$vP->produit->marque->categorie->libele }} {{$vP->produit->marque->libele }} {{$vP->produit->libele }} 
                </td>
                <td>
                  {{ $vP->duree }} 
                </td>
                <td>
                  @php
                      $recette +=(float) $vP->montant;
                      $recetteDT +=(float)$vP->montant * (float)$vV->updateTaux;
                  @endphp
                @formaMille((float)$vV->updateTaux) Fc
                </td>
                <td>
                  @formaMille((float)$vP->montant) {{ $vV->devise->libele }}
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
        <!-- debut -->
         
         <!-- fin -->
        <tr>
          <td colspan="2" class="tb">Sous total après 17h30</td>
          <td colspan="2" class="tb">
            @php
                $recetteDT = (float) $recetteDT - (float)$restePaiementTrancheFcDT;
                $restePaiementTrancheFc +=(float)$restePaiementTrancheFcDT;
            @endphp
            @formaMille((float) $recetteDT) Fc
            </td>
          <td colspan="2" class="tb">
            @foreach ($rapport['depot']->devise as $cle=>$dev )
                   @formaMille( (float)$recetteDT/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                @endforeach
          </td>
        </tr>
        @php
          $recetteFc += (float)$recettePT+ (float)$recetteDT;
        @endphp
      @else
        @foreach ($rapport['vente'] as $kV=>$vV)
          @php
            $restePaiementTrancheFc += (float)$vV->paiement->sortByDesc('created_at')->first()->solde * (float)$vV->updateTaux;
          @endphp
            @foreach ($vV->reservationProduit as $kP=>$vP )
            <tr>
              <td>
                {{$vP->reservation->code}} <br>
                {{$vP->reservation->created_at}}
              </td>
              <td>
                {{$vP->produit->marque->categorie->libele }} {{$vP->produit->marque->libele }} {{$vP->produit->libele }} 
              </td>
              <td>
                {{ $vP->duree }} 
              </td>
              <td>
                @php
                    $recette +=(float) $vP->montant;
                    $recetteFc +=(float)$vP->montant * (float)$vV->updateTaux;
                @endphp
              @formaMille((float)$vV->updateTaux) Fc
              </td>
              <td>
                @formaMille((float)$vP->montant) {{ $vV->devise->libele }}
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
         @php
          $recetteFc -= (float)$restePaiementTrancheFc;
         @endphp
      @endif
     
    </tbody>
    <tfoot>
        <tr class="footer-row">
            <td colspan="2"><strong>Total Général</strong></td>
            <td colspan="2">
              @php
                $recetteFc += (float)$rapport['avanceTotal'];
              @endphp
                @formaMille( (float)$recetteFc) Fc
            </td>
              <td colspan="3">
                @foreach ($rapport['depot']->devise as $cle=>$dev )
                   @formaMille( (float)$recetteFc/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                @endforeach
              </td>
        </tr>
        @if($rapport ['avanceTotal']>0)
            <tr class="footer-row">
                <td colspan="2"><strong>Reçu P-Tranche Vente Antérieur</strong></td>
                <td colspan="2">
                    @formaMille( (float)$rapport ['avanceTotal']) Fc
                </td>
                  <td colspan="3">
                      @foreach ($rapport['depot']->devise as $cle=>$dev )
                          @formaMille( (float)$rapport ['avanceTotal']/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                      @endforeach
                  </td>
            </tr>
          @endif
        @if ($restePaiementTrancheFc >0)
          <tr class="footer-row">
              <td colspan="2"><strong>Reste P-Tranche</strong></td>
              <td colspan="2">
                  @formaMille( (float)$restePaiementTrancheFc) Fc
              </td>
                <td colspan="3">
                    @foreach ($rapport['depot']->devise as $cle=>$dev )
                        @formaMille( (float)$restePaiementTrancheFc/(float)$dev->taux ) {{ $dev->libele }} ({{ $dev->taux }}) <br>
                    @endforeach
                </td>
          </tr>
        @endif
    </tfoot>
  </table>        
  <h2>Tableau Classement Vendeur du mois {{ $depotLibele }}</h2>

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
    <!-- <h6>Pas de panique c'est zoura, je teste mon application</h6> -->
    <h6>@coryright zouracorp 2025</h6>
</body>
</html>
@php
// die();
@endphp