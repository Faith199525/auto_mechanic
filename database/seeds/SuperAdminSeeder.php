<?php

use Illuminate\Database\Seeder;
use App\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'codetouch@autoshop.api',
            'password' => bcrypt('autoshop')
        ]);
        
        $user->roles()->attach(5); //5 - superAdmin id
    }
}
