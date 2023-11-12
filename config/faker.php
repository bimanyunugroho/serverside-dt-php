<?php

require 'config.php';
require_once '../vendor/autoload.php';

$faker = Faker\Factory::create();

try {
    for ($i = 0; $i < 20; $i++) {
        // Generate data
        $namaPelanggan = $faker->firstNameMale();
        $waktuIssued = $faker->dateTimeThisMonth->format('Y-m-d H:i:s');
        $namaItem = $faker->word;
        $jenisTransaksi = $faker->randomElement(['T', 'P', 'O', 'K']);
        $kasir = $faker->randomElement(['1', '2']);
        $harga = round($faker->randomFloat(2, 1000, 10000));
        $qty = $faker->numberBetween(1, 10);
        $diskon = round($faker->randomFloat(2, 500, 2000));

        $total = round($harga * $qty);
        $subtotal = round($total - $diskon);
        $ppn = round($subtotal * 0.11);
        $totalAfterPpn = round($subtotal + $ppn);

        // Simpan data ke database
        $query = "INSERT INTO public.trx_item 
                (nama_pelanggan, waktu_issued, nama_item, jenis_transaksi, kasir, harga, qty, total, diskon, subtotal, ppn, total_after_ppn) 
                VALUES ('$namaPelanggan', '$waktuIssued', '$namaItem', '$jenisTransaksi', '$kasir', $harga, $qty, $total, $diskon, $subtotal, $ppn, $totalAfterPpn)";

        $result = pg_query($conn, $query);

        if (!$result) {
            throw new Exception(pg_last_error($conn));
        }
    }

    echo "1000 baris data berhasil disimpan ke database.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    pg_close($conn);
}