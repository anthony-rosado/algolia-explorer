<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $products = Product::factory(20)
            ->sequence(
                ['is_available' => true],
                ['is_available' => false],
            );

        $childrenCategories = Category::factory(10)->has($products);

        Category::factory(20)
            ->has($childrenCategories, 'children')
            ->create();
    }
}
