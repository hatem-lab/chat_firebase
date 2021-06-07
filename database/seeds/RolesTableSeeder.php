<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $superAdmin=\App\Role::create([
            'name'=>'super_admin',
            'display_name'=>'super_admin',
            'description'=>'this acount do any things',
        ]);
        $user=\App\Role::create([
            'name'=>'user',
            'display_name'=>'user',
            'description'=>'this acount do something spical ',
        ]);
    }
}
