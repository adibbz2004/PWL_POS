<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['kategori_kode' => 'KTG011', 'kategori_nama' => 'Busana Muslim'],
            ['kategori_kode' => 'KTG012', 'kategori_nama' => 'Barang Elektronik'],
            ['kategori_kode' => 'KTG013', 'kategori_nama' => 'Kuliner'],
            ['kategori_kode' => 'KTG014', 'kategori_nama' => 'Parfum'],
            ['kategori_kode' => 'KTG015', 'kategori_nama' => 'Perabotan'],
        ];
        DB::table('m_kategori')->insert($data);
    }
}