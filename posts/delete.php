<?php
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi variabel
$error = '';
$success = false;

// Inisialisasi session
session_start();

// Mengecek apakah ada parameter id di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $error = "ID artikel tidak ditemukan!";
} else {
    $id = (int)$_GET['id'];
    
    // Validasi id harus lebih besar dari 0
    if ($id <= 0) {
        $error = "ID artikel tidak valid!";
    } else {
        // Cek apakah post ada di database
        $check_query = "SELECT id, title FROM posts WHERE id = $id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (!$check_result || mysqli_num_rows($check_result) === 0) {
            $error = "Artikel tidak ditemukan!";
        } else {
            // Ambil judul post untuk pesan
            $post = mysqli_fetch_assoc($check_result);
            $post_title = $post['title'];
            
            // Query DELETE
            $delete_query = "DELETE FROM posts WHERE id = $id";
            
            if (mysqli_query($conn, $delete_query)) {
                $success = true;
                
                // Simpan pesan sukses ke session
                $_SESSION['message'] = "Artikel '" . htmlspecialchars($post_title) . "' berhasil dihapus!";
                $_SESSION['type'] = 'success';
                
                // Redirect ke index dengan pesan sukses
                header("Location: index.php");
                exit();
            } else {
                $error = "Gagal menghapus artikel: " . mysqli_error($conn);
            }
        }
    }
}

// Jika ada error, simpan ke session dan redirect kembali ke index
if (!empty($error)) {
    $_SESSION['message'] = $error;
    $_SESSION['type'] = 'error';
    
    header("Location: index.php");
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>
