<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>facture user - {{$findVenteDetail->user->name."". $findVenteDetail->created_at ."".$findVenteDetail->code  }}</title>
    <style>
        @page {
            margin: 0;
        }
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
        }
        .main{
            width: 80mm; /* largeur imprimable réelle */
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: left;
            /* background-color: wheat; */
        }
        .container{
            width: 90%;
        }
        .invoice-header, .invoice-footer p{
            /* line-height: .5; */
            text-align: center;
        }
        .sansInterligne{
            line-height: .5;
        }
        .logo{
            width: 100%; 
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-radius: 5px;
        }
        .logo img{
            width: auto;
            max-width: 70px;
            height: 50px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .trHead{
            border: 1px solid darkblue;
        }
        .tdDashed{
            border-bottom: 1px dashed darkblue;
        }
        .separated{
            height: 5px;
            margin: 5px;
            text-align: center;
            font-weight: 600;
        }
        .footer-cell, .left{
            text-align: left;
        }
        .right{
            text-align: right;
        }
        .center{
            text-align: center;
        }
        .imprime{
            text-align: center;
            font-size: 6px;
        }
    </style>
</head>
<body>
<div class="main">
            
    @php
        $quantite = 0;
        $netPaye = 0;
    @endphp
    @foreach ( $findVenteDetail->venteProduit as $val)
        @php
            $quantite +=$val->quantite;
            $netPaye+=$val->prixT;
        @endphp 
    @endforeach            


        <div class="container">
        <div class="invoice-header">
            <div class="logo">
                @if ($findVenteDetail->depot->logo)
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('uploads/logo/'.$findVenteDetail->depot->logo))) }}" alt="logo">
                @else
                    Shop {{$findVenteDetail->depot->libele}}
                @endif
            </div>
        <h3>Détails de la Vente</h3>
        <h3>Ets. Ujisha</h3>
        <div class="idnat">
            RCCM:KNG/RCCM/22-A-01256 ID.NAT : <br> 01-G4701-N998728, N.Impot: A23119069 <br>
            ***
            Adresse: Croisement Des Avenues Kasongolunda N°292 Et 24 Novembre (Liberation) Ref :Station KYONDO OIL en face de l'académie de beaux-arts. Kinshasa, RDC.
            ***  <br>
            Phone: +243822109929, +243808400183 <br>
            <!-- Contact@ujisha.com -->
        </div>
        <div class="sansInterligne">
            <p class="left"> 
                Facture établi par {{$findVenteDetail->user->name}} 
                @if($findVenteDetail->user->prenom !=null)
                    {{$findVenteDetail->user->prenom}}
                @else
                    {{$findVenteDetail->user->postnom}}
                @endif
            </p>
            <p class="left">Date : {{ $findVenteDetail->created_at }}</p>
            <p class="left">Client : {{ $findVenteDetail->client->name }} {{ $findVenteDetail->client->tel }}</p>
            <p class="left">Numéro de Facture : {{ $findVenteDetail->code }}</p>
        </div>
    </div>
        <div class="table-wrapper">
        <table class="table">
            <thead class="">
                <tr class="trHead">
                    <th  class="header-cell rounded-left">
                        Produit
                    </th>
                    <th  class="header-cell">
                        Qté
                    </th>
                    <th  class="header-cell rounded-right">
                        Prix
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach ($findVenteDetail->venteProduit as $item)
                <tr class="tdDashed">
                    <th >
                        {{$item->produit->marque->libele}} 
                        {{$item->produit->libele}}<br>
                        {{$item->produit->etat}}
                    </th>
                    <td >
                        {{$item->quantite}}
                    </td>
                    <td >
                        @formaMille($item->prixT) {{ $findVenteDetail->devise }}
                    </td>
                </tr>
            @endforeach
            <tr class="separated">
                <td colspan="3">Paiement (taux d'échange : {{  $findVenteDetail->taux }})</td>
            </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="left">Date</th>
                    <th class="right">Montant</th>
                    
                </tr>
                @if($findVenteDetail->paiement != null)

                    @foreach($findVenteDetail->paiement as $cle=>$valeur)
                        <tr>
                            <td colspan="2" class="left">{{$valeur->created_at}}</td>
                            <td class="center">@formaMille($valeur->avance) {{ $findVenteDetail->devise }}</td>
                        </tr>
                    @endforeach

                    @if($valeur->solde > 0)
                    <tr>
                        <th colspan="2" class="left">Reste</th>
                        <th>@formaMille($valeur->solde) {{ $findVenteDetail->devise }}</th>
                    </tr>
                    @endif
                @endif
                <tr class="footer-row trHead">
                    <th class="footer-cell" colspan="2">Total</th>
                    <th class="footer-cell">
                        ({{ $findVenteDetail->devise }}) @formaMille($netPaye)<br> 
                        (cdf) @formaMille($netPaye*$findVenteDetail->taux)
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="invoice-footer">
        
        <p>
            Merci pour votre achat ! <br>
            Les marchandises vendue sont ni reprises ni échangées!<br>
            1 mois de garentie pour les ordinateurs, celle-ci n'inclut pas le display et chargeur!
        </p>
        <p class="imprime"> imprimer par {{Auth::user()->name ." ".Auth::user()->postnom ." ".Auth::user()->prenom}}</p>
    </div>
</div>
</body>
</html>