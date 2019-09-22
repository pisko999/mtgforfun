<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('statuses')->insert([
            'status'=>'Cart',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Rebuy',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Want',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Commanded',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Waiting payment',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Paid',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Preparing',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Send',
        ]);
        DB::table('statuses')->insert([
            'status'=>'Delivered',
        ]);
    }
}
