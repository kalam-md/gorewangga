<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [];

        // Loop dari jam 00:00 sampai 23:00
        for ($i = 0; $i < 24; $i++) {
            $jam = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';

            $data[] = [
                'jam' => $jam,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert ke database
        DB::table('jadwals')->insert($data);
    }
}
