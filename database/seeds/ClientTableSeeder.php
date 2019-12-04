<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $clients = ['ahmed', 'mohammed', 'amira', 'aya'];
       foreach ($clients as $client){
           \App\Client::create([
               'name' => $client,
               'phone' => '011111167',
               'address' => 'tahta',

           ]);

       }
    }
}
