<?php
// koneksi.php

$host = 'localhost';
$username = 'root';
$password = ''; // Sesuaikan dengan password root Anda
$db_name = 'blogs';

// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $db_name);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
