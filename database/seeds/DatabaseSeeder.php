<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        DB::table('users')->insert([
            'name' => 'Ans Kevin Caneso',
            'email' => 'admin@me.com',
            'type' => 'Admin',
            'password' => Hash::make('11111111'),
        ]);
    }
}
