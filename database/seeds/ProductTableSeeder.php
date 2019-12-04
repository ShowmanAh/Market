<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $products = ['product1', 'product2','product3'];
       foreach ($products as $product){}
       \App\Product::create([
           'category_id' => 1,
           //translation product
           'ar' => ['name' => $product, 'description' => $product . '.desc'],
           'en' => ['name' => $product, 'description' => $product . '.desc'],
           'purchase_price' => 100,
           'sale_price' => 150,
           'stock' => 100,

       ]);
    }
}
