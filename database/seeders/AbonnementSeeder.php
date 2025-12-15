<?php

namespace Database\Seeders;

use App\Models\Abonnement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AbonnementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abonnements =  [
            [
                "name"=>"Standard",
                "prix"=>40,
                "max"=>3,
                "description"=>"30$ par mois pour 3 points de vente max"
            ],
            [
                "name"=>"Pro",
                "prix"=>60,
                "max"=>7,
                "description"=>"60$ par mois pour 7 points de vente max"
            ],
            [
                "name"=>"Pro+",
                "prix"=>80,
                "max"=>10,
                "description"=>"80$ par mois pour 10 points de vente"
            ],
            [
                "name"=>"Prenium",
                "prix"=>100,
                "max"=>20,
                "description"=>"Max 20 points de vente"
            ],
        ];

         foreach ($abonnements as $abonnement) {
            Abonnement::firstOrCreate($abonnement);
        }
    }
}
