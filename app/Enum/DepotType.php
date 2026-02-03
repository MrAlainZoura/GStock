<?php

namespace App\Enum;

enum DepotType: string {
    case Alimentation = 'Alimentation';
    case Bar = 'Bar';
    case BoiteDeNuit = 'Boite de nuit';
    case Boutique = 'Boutique';
    case Entrepot = 'Entrepot';
    case Hotel = 'Hotel';
    case Restaurant = 'Restaurant';
    case SalleDeSport = 'Salle de Sport';
    case Salon = 'Salon';
    case SalleDeJeu = 'Salle de Jeu';
    case Shop = 'Shop';
    case Snack = 'Snack';
    case Terrasse = 'Terrasse';

    public function label(): string {
        return match($this) {
            self::Alimentation => 'Alimentation',
            self::Bar => 'Bar',
            self::BoiteDeNuit => 'Boite de nuit',
            self::Boutique => 'Boutique',
            self::Entrepot => 'Entrepôt',
            self::Hotel => 'Hôtel',
            self::Restaurant => 'Restaurant',
            self::SalleDeSport => 'Salle de Sport',
            self::Salon => 'Salon',
            self::SalleDeJeu => 'Salle de Jeu',
            self::Shop => 'Shop',
            self::Snack => 'Snack',
            self::Terrasse => 'Terrasse',
        };
    }

}

