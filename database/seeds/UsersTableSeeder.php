<?php

use Illuminate\Database\Seeder;
use App\user;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	User::create([
    		'name' => 'Dany',
    		'email' => 'fadilahdany@gmail.com',
    		'address' => 'Parungkuda',
    		'born' => '2002-06-20',
    		'hobby' => 'Tidur',
    		'phone' => '081295206633',
    		'password' => bcrypt('secret')
    	]);
    }
}
