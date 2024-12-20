<?php

namespace Database\Seeders;

use App\Models\MenuModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuModel::create([
            'menu_name' => 'Menu Baru',
            'menu_link' => 'admin-menu-baru',
            'menu_icon' => '<i class="fa-solid fa-users-gear"></i>',
            'parent_id' => null
        ]);
    }
}
