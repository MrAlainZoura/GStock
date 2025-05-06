<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles de base */
.container {
    max-width: 300px; 
    margin: 2.5rem auto; /* Equivalent de mt-10 */
}

.table-wrapper {
    width: 100%; /* Equivalent de w-full */
}

.table {
    width: 100%;
    text-align: left; /* Equivalent de text-left */
    color: #6b7280; /* Equivalent de text-gray-500 */
    border-collapse: collapse; /* Pour éviter les espaces entre les cellules */
}

.table th, .table td {
    border: 1px solid rgb(21, 22, 23); /* Bordure légère autour des cellules */
    text-align: left;
    align-items: left;
    justify-content: left;
    justify-items: left;
    color: black;
    font-weight: bold;
}

.header-cell {
    padding: 1px; /* Equivalent de px-6 py-3 */
    text-align: left;
}

.rounded-left {
    border-top-left-radius: 0.5rem; /* Equivalent de rounded-s-lg */
    border-bottom-left-radius: 0.5rem;
}

.rounded-right {
    border-top-right-radius: 0.5rem; /* Equivalent de rounded-e-lg */
    border-bottom-right-radius: 0.5rem;
}

.footer-row {
    font-weight: 600; /* Equivalent de font-semibold */
    text-align: left;
}

.footer-cell {
    padding: 1px; /* Equivalent de px-3 py-3 */
}

/* Styles pour le mode sombre */
@media (prefers-color-scheme: dark) {

    .footer-row {
        color:rgb(6, 5, 5); /* Equivalent de dark:text-white */
        font-size: bold;
        text-align: left;
    }
}

    </style>
</head>
<body>
<div class="mx-auto mt-10">
            
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
            <img src="{{public_path('img/icon.jpeg')}}" alt="" style="width: 20px; height:20px;">
        <h2>Détails de la Facture</h2>
        <h3>Ste. Ujisha ...</h3>
        <p> Facture effectué par {{$findVenteDetail->user->name}} 
            @if($findVenteDetail->user->prenom !=null)
                {{$findVenteDetail->user->prenom}}
            @else
                {{$findVenteDetail->user->postnom}}
            @endif
        </p>
        <p>Date : {{ $findVenteDetail->created_at }}</p>
        <p>Client : {{ $findVenteDetail->client->name }} {{ $findVenteDetail->client->tel }}</p>
        <p>Numéro de Facture : {{ $findVenteDetail->code }}</p>
    </div>
        <div class="table-wrapper">
        <table class="table">
            <thead class="">
                <tr>
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
                <tr class="">
                    <th >
                        {{$item->produit->marque->libele}} 
                        {{$item->produit->libele}}<br>
                        {{$item->produit->etat}}
                    </th>
                    <td >
                        {{$item->quantite}} pc
                    </td>
                    <td >
                        @formaMille($item->prixT) Fc
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr class="footer-row">
                    <th class="footer-cell">Total</th>
                    <td class="footer-cell">
                        {{$quantite}} pc
                    </td>
                    <td class="footer-cell"> @formaMille($netPaye) Fc</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="invoice-footer">
        <p>Merci pour votre achat !</p>
        <p>Les marchandises vendue sont ni...</p>
        <p>Contact : +243 80...</p>
    </div>
</div>
</body>
</html>