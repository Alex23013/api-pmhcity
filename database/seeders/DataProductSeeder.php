<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Material;
use App\Models\Size;
use App\Models\StatusProduct;
use Illuminate\Database\Seeder;

class DataProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed Brands
        $brands = [
            'Kiabi', 'Zara', 'H&M', 'Nike', 'Orchestra', 'Tape à l\'œil',
            'Gémo', 'Matière africaine', 'Pokémon', 'Jennyfer', 'Vertbaudet',
            'Okaïdi', 'Sergent Major', 'Primark', 'Disney', 'adidas', 'Pimkie',
            'Camaïeu', 'Bershka', 'Mango', 'In Extenso', 'Cache Cache',
            'Pull & Bear', 'Obaïbi', 'Decathlon', 'Promod', 'Levi\'s', 'Puma',
            'Petit Bateau', 'sansnom.', 'Du Pareil au Même', 'IKKS', 'Tissaia',
            'Stradivarius', 'Boutique Parisienne', 'Playmobil', 'TEX', 'Etam',
            'C&A', 'Vintage Dressing', 'Fait Main', 'TAO', 'Naf Naf', 'LEGO',
            'Nintendo', 'Tommy Hilfiger', 'Jacadi', 'Ralph Lauren', 'Lacoste',
            'Jules', 'Pas de marque indiquée', 'Sans marque', 'Autre',
        ];
        
        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }

        // Seed Sizes
        $sizes = [
            'XXXS / 30 / 2', 'XXS / 32 / 4', 'XS / 34 / 6', 'S / 36 / 8',
            'M / 38 / 10', 'L / 40 / 12', 'XL / 42 / 14', 'XXL / 44 / 16',
            'XXXL / 46 / 18', '4XL / 48 / 20', '5XL / 50 / 22', '6XL / 52 / 24',
            '7XL / 54 / 26', '8XL / 56 / 28', '9XL / 58 / 30', 'Taille unique', 'Autre'
        ];
        foreach ($sizes as $size) {
            Size::firstOrCreate(['name' => $size]);
        }

        // Seed StatusProduct
        $statusProducts = ['Neuf', 'Très bon état'];
        foreach ($statusProducts as $status) {
            StatusProduct::firstOrCreate(['name' => $status]);
        }

        // Seed Materials
        $materials = [
            'Acier', 'Acrylique', 'Alpaga', 'Argent', 'Bambou', 'Bois', 'Cachemire', 'Caoutchouc',
            'Carton', 'Coton', 'Cuir', 'Cuir synthétique', 'Cuir verni', 'Céramique', 'Daim', 'Denim',
            'Dentelle', 'Duvet', 'Fausse fourrure', 'Feutre', 'Flanelle', 'Jute', 'Laine', 'Latex',
            'Lin', 'Maille', 'Mohair', 'Mousse', 'Mousseline', 'Mérinos', 'Métal', 'Nylon', 'Néoprène',
            'Or', 'Paille', 'Papier', 'Peluche', 'Pierre'
        ];
        foreach ($materials as $material) {
            Material::firstOrCreate(['name' => $material]);
        }

        echo "DataProducSeeder executed successfully!\n";
    }
}
