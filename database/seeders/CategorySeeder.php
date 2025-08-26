<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Subcategory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if(app()->environment('local')){
            DB::table('subcategories')->truncate();
            DB::table('categories')->truncate();
        }
        $categories = [
            'Male' => ['Vêtements', 'Chaussures', 'Accessoires'],
            'Female' => ['Clothing', 'Shoes', 'Bags', 'Accessories'],
            'Children' => ['Filles', 'Garçons', 'Jouets', 'Porteurs, trotteurs et jouets à bascule', 'Mobilier enfant', 'Autres'],
            'House' => ['Textiles', 'Décoration', 'Arts de la table', 'Célébrations et fêtes', 'Mobilier'],
            'Electronic' => ['Accessoires', 'Appareils photo et accessoires', 'Accessoires informatiques', 'Liseuses'],
            'Entertainment' => ['Puzzles','Jeux de plateau et jeux miniatures','Livres','Musique','Vidéo']
        ];

        foreach ($categories as $categoryName => $subcategories) {
            // Create the category
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($subcategories as $subcategoryName) {
                // Create each subcategory and link it to the category
                Subcategory::firstOrCreate([
                    'name' => $subcategoryName,
                    'category_id' => $category->id
                ]);
            }
        }
    }
}