<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Styles de base */
.container {
    max-width: 600px; /* Equivalent de max-w-md */
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
    border: 1px solid #d1d5db; /* Bordure légère autour des cellules */
}
.table-header {
    text-transform: uppercase; /* Equivalent de uppercase */
    background-color: #f3f4f6; /* Equivalent de bg-gray-100 */
    color: #374151; /* Equivalent de text-gray-700 */
}

.header-cell {
    padding: 0.75rem 1.5rem; /* Equivalent de px-6 py-3 */
}

.rounded-left {
    border-top-left-radius: 0.5rem; /* Equivalent de rounded-s-lg */
    border-bottom-left-radius: 0.5rem;
}

.rounded-right {
    border-top-right-radius: 0.5rem; /* Equivalent de rounded-e-lg */
    border-bottom-right-radius: 0.5rem;
}

.table-row {
    background-color: #ffffff; /* Equivalent de bg-white */
}

.row-cell {
    padding: 0.5rem 1rem; /* Equivalent de px-3 py-2 */
    color: #111827; /* Equivalent de text-gray-900 */
}

.footer-row {
    font-weight: 600; /* Equivalent de font-semibold */
    color: #111827; /* Equivalent de text-gray-900 */
}

.footer-cell {
    padding: 0.75rem 1rem; /* Equivalent de px-3 py-3 */
}

/* Styles pour le mode sombre */
@media (prefers-color-scheme: dark) {
    .table-header {
        background-color: #374151; /* Equivalent de dark:bg-gray-700 */
        color: #d1d5db; /* Equivalent de dark:text-gray-400 */
    }
    .table-row {
        background-color: #1f2937; /* Equivalent de dark:bg-gray-800 */
    }
    .row-cell {
        color: #ffffff; /* Equivalent de dark:text-white */
    }
    .footer-row {
        color: #ffffff; /* Equivalent de dark:text-white */
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
            <thead class="table-header">
                <tr>
                    <th scope="col" class="header-cell rounded-left">
                        Produit
                    </th>
                    <th scope="col" class="header-cell">
                        Qté
                    </th>
                    <th scope="col" class="header-cell rounded-right">
                        Prix
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach ($findVenteDetail->venteProduit as $item)
                <tr class="table-row">
                    <th scope="row" class="row-cell">
                        {{$item->produit->marque->libele}} 
                        {{$item->produit->libele}}<br>
                        {{$item->produit->etat}}
                    </th>
                    <td class="row-cell">
                        {{$item->quantite}} pc
                    </td>
                    <td class="row-cell">
                        @formaMille($item->prixT) Fc
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
                <tr class="footer-row">
                    <th scope="row" class="footer-cell">Total</th>
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