<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministratorsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('administrators')->insert([
            'name' => 'senbiki akifumi',
            'email' => 'akifumi.senbiki.1209@gmail.com',
            'password' => bcrypt('AAAaaa123'),
        ]);
    }
}
