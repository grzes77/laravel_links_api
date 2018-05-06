<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        for($i=1; $i<11; $i++){
            $product = new \App\Product();
            $product->name = 'test'.' '.$i;
            $product->price = 100;
            $product->save();
        }
    }
}
