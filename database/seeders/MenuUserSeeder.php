<?php

namespace Database\Seeders;

use App\Models\MenuUserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuUserModel::create([
            'jenisUser_id' => 3,
            'menu_id' => 1,
        ]);
        MenuUserModel::create([
            'jenisUser_id' => 3,
            'menu_id' => 1,
        ]);
        MenuUserModel::create([
            'jenisUser_id' => 3,
            'menu_id' => 1,
        ]);

    }
}
