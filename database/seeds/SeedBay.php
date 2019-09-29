<?php

use Illuminate\Database\Seeder;

class SeedBay extends Seeder
{
    public function run()
    {
        factory(\Aeroparks\Bay\Models\Zone::class, 5)->create()->each(function($zone,$key){
            $zone->name = $zone->name."000{$key}";
            $zone->slug = $zone->slug."000{$key}";
            $zone->save();

            factory(\Aeroparks\Bay\Models\Bay::class, 10)->create()->each(function($bay,$k) use($key,$zone){
                $bay->name = $bay->name."00{$key}{$k}";
                $bay->save();
                $zone->bay()->save($bay);
                
                dump($zone->name."----".$bay->name);
            });
        });
    }
}
