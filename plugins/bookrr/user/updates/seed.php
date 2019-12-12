<?php namespace Bookrr\Keeprr\Updates;


use October\Rain\Database\Updates\Seeder;
use Backend\Models\UserRole;

class SeedKeeprrTables extends Seeder
{
    public function run(){
        UserRole::updateOrCreate([
            'name'        => 'Customer',
            'code'        => 'customer',
            'description' => 'User as customer.',
        ]);

        UserRole::updateOrCreate([
            'name'        => 'Staff',
            'code'        => 'staff',
            'description' => 'User as staff.',
        ]);
    }
}