<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_supplier' => 'PT Niki Joyo', 'alamat' => 'Malang'],
            ['nama_supplier' => 'Princess Fazion', 'alamat' => 'Malang'],
            ['nama_supplier' => 'Distributor Makanan', 'alamat' => 'Malang'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}