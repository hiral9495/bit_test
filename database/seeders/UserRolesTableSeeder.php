<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_roles')->insert([
            ['id' => 1, 'user_type' => 'Super Admin', 'created_at' => Carbon::parse('2024-08-01 07:34:49'), 'updated_at' => null],
            ['id' => 2, 'user_type' => 'Admin', 'created_at' => Carbon::parse('2024-08-01 07:35:08'), 'updated_at' => null],
            ['id' => 3, 'user_type' => 'Editor', 'created_at' => Carbon::parse('2024-08-01 07:35:19'), 'updated_at' => null],
            ['id' => 4, 'user_type' => 'Member', 'created_at' => Carbon::parse('2024-08-01 07:35:28'), 'updated_at' => null],
        ]);
    }
}
