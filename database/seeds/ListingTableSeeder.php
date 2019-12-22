<?php

use Illuminate\Database\Seeder;

class ListingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user =  App\User::create([
            'name' => "User Second",
            'email' => 'user2@gmail.com',
            'password' => bcrypt('password'),
            'type' => 'u'
        ]);

        DB::table('listing')->insert([
            'list_name' => "Starbucks @ Mid Valley Megamall",
            'address' => 'Lingkaran Syed Putra, Mid Valley City',
            'latitude' => 3.117880,
            'longitude' => 101.676749,
            'submitter_id' => $user->id
        ]);
    }
}
