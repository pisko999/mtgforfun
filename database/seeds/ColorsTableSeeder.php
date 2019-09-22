<?php

use Illuminate\Database\Seeder;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('colors')->insert(['color'=>'White',]);
        DB::table('colors')->insert(['color'=>'Blue',]);
        DB::table('colors')->insert(['color'=>'Black',]);
        DB::table('colors')->insert(['color'=>'Red',]);
        DB::table('colors')->insert(['color'=>'Green',]);
    }
}
