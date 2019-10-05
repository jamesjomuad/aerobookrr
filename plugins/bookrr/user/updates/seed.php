<?php namespace Bookrr\User\Updates;


use October\Rain\Database\Updates\Seeder;
use Backend\Models\UserRole;

class SeedAllTables extends Seeder
{

    public function run(){
        $this->seedRoles();
    }

    private function seedRoles() {
        UserRole::firstOrCreate([
            'name'        => 'Staff',
            'code'        => 'staff',
            'description' => 'Manage business system workflow.',
        ]);
        UserRole::firstOrCreate([
            'name'        => 'Customer',
            'code'        => 'customer',
            'description' => 'Manage customer ability for the system.',
        ]);
        UserRole::firstOrCreate([
            'name'        => 'Demo',
            'code'        => 'demo',
            'description' => 'Allow user to explore in demo mode.',
        ]);
        UserRole::firstOrCreate([
            'name'        => 'Frontend',
            'code'        => 'frontend',
            'description' => 'Frontend authentication.',
        ]);
    }

} 