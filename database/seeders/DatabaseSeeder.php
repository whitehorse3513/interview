<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            [
                'name'              => 'admin',
                'email'             => 'admin@gmail.com',
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password'          => bcrypt('password'),
                'created_at'        => date("Y-m-d H:i:s"),
                'updated_at'        => date("Y-m-d H:i:s")
            ]
        ]);

    }
}
