<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'customer']);

        
        $mattresses = Category::create([    //this is the first subcategory (just for test)
            'name' => 'Mattresses',
            'slug' => 'mattresses',
            'status' => 'active',
        ]);

        $pillows = Category::create([       //this is the second subcategory (just for test)
            'name' => 'Pillows',
            'slug' => 'pillows',
            'status' => 'active',
        ]);

        Product::create([                   //this is product info from mattresses subcategory
            'category_id' => $mattresses->id,
            'name' => 'Luxury Memory Foam Mattress',
            'slug' => 'luxury-memory-foam-mattress',
            'price' => 499.99,
            'stock' => 20,
            'status' => 'active',
            'featured' => true,
        ]);
        Product::create([                   //this is product info from pillows subcategory
            'category_id' => $pillows->id,
            'name' => 'Cooling Gel Pillow',
            'slug' => 'cooling-gel-pillow',
            'price' => 39.99,
            'stock' => 50,
            'status' => 'active',
        ]);
    }
}
