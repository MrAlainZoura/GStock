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
        $depotLiebele ="";
        if (count( $rapport['vendeurs']) >0) {
          $depotLiebele = $rapport['vendeurs'][0]->depot->libele;
        }
    
    @endphp
  <h2>Rapport {{ $rapport['periode'] }}  {{ $depotLiebele }} </h2>
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
      @if (is_array($rapport['vente']) && array_key_exists('avant', $rapport['vente']))
          @foreach ($rapport['vente']['avant'] as $kV=>$vV)
          @php
            $restePaiementTrancheFcPT += (float)$vV->paiement->sortByDesc('created_at')->first()->solde * (float)$vV->updateTaux;
          @endphp
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
                      $recettePT +=(float)$vP->prixT * (float)$vV->updateTaux;
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
        <!-- debut -->
          @if ($rapport ['compassassion']['avant']->count() > 0)
            <tr>
              <td colspan="6" style="font-weight: bold; font-size: 18px;">Compassassion avant / Contre valeur</td>
            </tr>
            @foreach ($rapport ['compassassion']['avant'] as $vcomp)
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
                      $recetteDT +=(float)$vP->prixT * (float)$vV->updateTaux;
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
        <!-- debut -->
          @if ($rapport ['compassassion']['apres']->count() > 0)
            <tr>
              <td colspan="6" style="font-weight: bold; font-size: 18px;">Compassassion / Contre valeur</td>
            </tr>
            @foreach ($rapport ['compassassion']['apres'] as $vcomp)
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
                          $recetteDT +=(float) $ajout * (float) $vcomp->updateTaux;
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
         @php
          $recetteFc -= (float)$restePaiementTrancheFc;
         @endphp
      @endif
      <!-- compassassion mois ou annee-->
      @if (!is_array($rapport ['compassassion']))
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
            <!-- <td colspan="2">—</td> -->
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
  
  <h2>Tableau de Présence {{ $rapport['periode'] }} {{ $depotLiebele }}</h2>
  @if (str_contains($rapport['periode'], "Journalier") == false)
    <h3>Statistique globale</h3> 
    <table class="table-style">
      <thead>
        <tr class="header-row">
          <th>N°</th>
          <th>Nom complet</th>
          <th>Présence</th>
          <th>Absence</th>
        </tr>
      </thead>
      <tbody>
        @php
          $numerotation = 1;
        @endphp
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
              @heure($detail->updated_at)
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

  @if ((int)$rapport['showVente'] > 0)
    <h2>Tableau de resumé de produit vendu {{ $depotLiebele }}</h2>
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