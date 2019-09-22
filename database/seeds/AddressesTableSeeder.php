<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->insert([
            'street'=>'Komenskeho',
            'number'=>'17',
            'flat'=>'15',
            'city'=>'Zdar nad Sazavou',
            'country'=>'Cesko',
            'region'=>'Vysocina',
            'postal'=>'59101',
        ]);
        DB::table('addresses')->insert([
            'street'=>'Hamry nad Sazavou',
            'number'=>'282',
            'flat'=>'1',
            'city'=>'Zdar nad Sazavou',
            'country'=>'Cesko',
            'region'=>'Vysocina',
            'postal'=>'59101',
        ]);
        DB::table('addresses')->insert([
            'street'=>'U Hajku',
            'number'=>'348',
            'flat'=>'1',
            'city'=>'Krucemburk',
            'country'=>'Cesko',
            'region'=>'Vysocina',
            'postal'=>'58266',
        ]);

    }
}
