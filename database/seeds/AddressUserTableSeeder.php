<?php

use Illuminate\Database\Seeder;

class AddressUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('address_user')->insert(['user_id' => 1, 'address_id' => 1]);
        DB::table('address_user')->insert(['user_id' => 1, 'address_id' => 2]);
        DB::table('address_user')->insert(['user_id' => 1, 'address_id' => 3]);

        DB::table('address_user')->insert(['user_id' => 2, 'address_id' => 1]);
        DB::table('address_user')->insert(['user_id' => 2, 'address_id' => 2]);
        DB::table('address_user')->insert(['user_id' => 2, 'address_id' => 3]);

        DB::table('address_user')->insert(['user_id' => 3, 'address_id' => 1]);
        DB::table('address_user')->insert(['user_id' => 3, 'address_id' => 2]);
        DB::table('address_user')->insert(['user_id' => 3, 'address_id' => 3]);

    }
}
