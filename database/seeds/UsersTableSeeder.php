<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'Spinar',
            'forename'=>'Petr',
            'country_code'=>'+33',
            'phone'=>'661024946',
            'address_id'=>1,
            'email'=>'spinarp@gmail.com',
            'password'=>bcrypt('spinar'),
            'role'=>4,
        ]);
        DB::table('users')->insert([
            'name'=>'Spinar',
            'forename'=>'Samuel',
            'country_code'=>'+420',
            'phone'=>'736679424',
            'address_id'=>1,
            'email'=>'samspinar@gmail.com',
            'password'=>bcrypt('spinar'),
            'role'=>2,
        ]);
        DB::table('users')->insert([
            'name'=>'Dymackova',
            'forename'=>'Lucie',
            'country_code'=>'+32',
            'phone'=>'489279688',
            'address_id'=>1,
            'email'=>'luckadym@seznam.com',
            'password'=>bcrypt('spinar'),
            'role'=>1,
        ]);
    }
}
