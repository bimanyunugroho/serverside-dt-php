<?php

$host = "localhost";
$dbname = "mvc";
$port = "5432";
$user = "postgres";
$password = "";

// Membuat koneksi
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi Gagal: " . pg_last_error());
}

?>
