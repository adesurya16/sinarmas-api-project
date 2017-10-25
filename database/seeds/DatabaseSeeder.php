<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(eventsTableSeeder::class);
        $this->call(treespeciesTableSeeder::class);
        $this->call(sponsorTableSeeder::class);
        $this->call(verifiedUserTableSeeder::class);
    }
}
