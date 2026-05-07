<?php
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi variabel
$error = '';
$success = false;

// Mengecek apakah ada parameter id di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = "ID kategori tidak ditemukan!";
} else {
    $id = (int)$_GET['id'];
    
    // Validasi id harus lebih besar dari 0
    if ($id <= 0) {
        $error = "ID kategori tidak valid!";
    } else {
        // Cek apakah kategori ada di database
        $check_query = "SELECT id, name FROM categories WHERE id = $id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (!$check_result || mysqli_num_rows($check_result) === 0) {
            $error = "Kategori tidak ditemukan!";
        } else {
            // Ambil nama kategori untuk pesan
            $category = mysqli_fetch_assoc($check_result);
            $category_name = $category['name'];
            
            // Query DELETE
            $delete_query = "DELETE FROM categories WHERE id = $id";
            
            if (mysqli_query($conn, $delete_query)) {
                $success = true;
                // Redirect ke index dengan pesan sukses
                header("Location: index.php?success=Kategori '$category_name' berhasil dihapus!");
                exit();
            } else {
                $error = "Gagal menghapus kategori: " . mysqli_error($conn);
            }
        }
    }
}

// Jika ada error, redirect kembali ke index
if (!empty($error)) {
    header("Location: index.php?error=" . urlencode($error));
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>
