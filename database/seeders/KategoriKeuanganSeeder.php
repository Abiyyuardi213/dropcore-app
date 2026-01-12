<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriKeuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Pemasukkan
            ['nama' => 'Penjualan Produk', 'tipe' => 'pemasukkan', 'kode' => '4001', 'deskripsi' => 'Pendapatan dari penjualan langsung'],
            ['nama' => 'Jasa Layanan', 'tipe' => 'pemasukkan', 'kode' => '4002', 'deskripsi' => 'Pendapatan dari jasa instalasi/maintenance'],
            ['nama' => 'Pemasukkan Lain-lain', 'tipe' => 'pemasukkan', 'kode' => '4003', 'deskripsi' => 'Pendapatan non-operasional'],

            // Pengeluaran
            ['nama' => 'Pembelian Stok', 'tipe' => 'pengeluaran', 'kode' => '5001', 'deskripsi' => 'Pembelian barang dagangan'],
            ['nama' => 'Gaji Karyawan', 'tipe' => 'pengeluaran', 'kode' => '5101', 'deskripsi' => 'Biaya gaji dan upah'],
            ['nama' => 'Operasional Kantor', 'tipe' => 'pengeluaran', 'kode' => '5201', 'deskripsi' => 'Listrik, Air, Internet, ATK'],
            ['nama' => 'Transportasi & Dinas', 'tipe' => 'pengeluaran', 'kode' => '5202', 'deskripsi' => 'Bahan bakar, tol, parkir'],
            ['nama' => 'Biaya Lain-lain', 'tipe' => 'pengeluaran', 'kode' => '5999', 'deskripsi' => 'Pengeluaran tak terduga'],
        ];

        foreach ($categories as $cat) {
            DB::table('kategori_keuangan')->insert([
                'id' => (string) Str::uuid(),
                'nama' => $cat['nama'],
                'tipe' => $cat['tipe'],
                'kode' => $cat['kode'],
                'deskripsi' => $cat['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
