<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;
use \App\Laravue\Acl;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@lager.exoquo.com',
            'password' => Hash::make('lager'),
        ]);

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $admin->syncRoles($adminRole);
    }
}
