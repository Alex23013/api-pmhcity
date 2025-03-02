<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReservationStatus;

class ReservationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            ['name' => 'created', 'display_name' => 'Votre réservation a été envoyée au vendeur. Veuillez attendre la confirmation du stock.'],
            ['name' => 'accepted', 'display_name' => 'Votre réservation a été confirmée par le vendeur. Vous pouvez procéder au paiement.'],
            ['name' => 'declined', 'display_name' => 'Malheureusement, le vendeur a refusé votre demande.'],
            ['name' => 'payed', 'display_name' => 'Le paiement a été effectué avec succès.'],
            ['name' => 'in transit', 'display_name' => 'Votre produit est en transit par le mode de livraison que vous avez choisi.'],
            ['name' => 'delivered', 'display_name' => 'Votre produit a été livré'],
        ];

        foreach ($statuses as $status) {
            ReservationStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
