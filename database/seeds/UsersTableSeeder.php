<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=\App\User::create([
            'name'=>'super_admin',
            'email'=>'super_admin@gmail.com',
            'password'=>bcrypt('secret'),


        ]);
       // $user->roles()->attach($superAdmin->id);
       // $user->attachRole('super_admin');

    }

}
