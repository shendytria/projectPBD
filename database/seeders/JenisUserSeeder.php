<?php

namespace Database\Seeders;

use App\Models\JenisUserModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisUserModel::create([
            'jenis_user' => 'admin'
        ]);
        JenisUserModel::create([
            'jenis_user' => 'guest'
        ]);
        JenisUserModel::create([
            'jenis_user' => 'it'
        ]);
    }
}
