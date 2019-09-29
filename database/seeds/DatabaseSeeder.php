<?php
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /*
        *   Seed Bay and Zones
        */
        $this->call(SeedBay::class);
    }
}