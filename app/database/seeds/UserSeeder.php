<?php
/**
 * Created by PhpStorm.
 * User: kaso
 * Date: 1/7/2015
 * Time: 4:16 PM
 */

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        if(!User::whereEmail('admin@supergeeks.com.ng')->first()) {
            $user = User::Create(
                [
                    'email'    => 'admin@supergeeks.com.ng',
                    'password' => 'Superg33ks',
                    'type'     => "admin"
                ]);
        }

    }

}
