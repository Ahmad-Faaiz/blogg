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
        // Cek apakah post ada di database menggunakan prepared statement
        $check_query = "SELECT id, title FROM posts WHERE id = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        
        if (!$check_stmt) {
            $error = "Error query: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($check_stmt, "i", $id);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) === 0) {
                $error = "Artikel tidak ditemukan!";
            } else {
                // Ambil judul post untuk pesan
                $post = mysqli_fetch_assoc($check_result);
                $post_title = $post['title'];
                
                // Query DELETE menggunakan prepared statement
                $delete_query = "DELETE FROM posts WHERE id = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_query);
                
                if (!$delete_stmt) {
                    $error = "Error query: " . mysqli_error($conn);
                } else {
                    mysqli_stmt_bind_param($delete_stmt, "i", $id);
                    
                    if (mysqli_stmt_execute($delete_stmt)) {
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
                    
                    mysqli_stmt_close($delete_stmt);
                }
            }
            
            mysqli_stmt_close($check_stmt);
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
