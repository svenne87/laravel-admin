<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() !== 'production') {
            $user = User::create([
                'id'            => 1,
                'name'          => 'Emil',
                'password'      => bcrypt('password'),
                'email'         => 'super-admin@example.com',
            ]);
        }
    }
}
