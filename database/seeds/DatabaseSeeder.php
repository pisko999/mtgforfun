<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AddressesTableSeeder::class,
            UsersTableSeeder::class,
            ColorsTableSeeder::class,
            StatusesTableSeeder::class,
            AddressUserTableSeeder::class,
            EditionsTableSeeder::class,

            mkmSeeder::class,
        ]);
    }
}
