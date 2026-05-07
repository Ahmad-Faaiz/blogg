<?php
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi session
session_start();

// Inisialisasi variabel
$errors = [];
$success = false;

// Mengecek apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Mengambil dan membersihkan input dari form
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    
    // Validasi id
    if ($id <= 0) {
        $errors[] = "ID artikel tidak valid";
    }
    
    // Validasi input
    if (empty($title)) {
        $errors[] = "Judul artikel tidak boleh kosong";
    }
    
    if (empty($content)) {
        $errors[] = "Konten artikel tidak boleh kosong";
    }
    
    if ($category_id <= 0) {
        $errors[] = "Kategori harus dipilih";
    }
    
    // Jika tidak ada error, lanjutkan ke update
    if (empty($errors)) {
        
        // Cek apakah post ada di database
        $check_query = "SELECT id FROM posts WHERE id = $id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (!$check_result || mysqli_num_rows($check_result) === 0) {
            $errors[] = "Artikel tidak ditemukan";
        } else {
            
            // Escape input untuk keamanan
            $title = mysqli_real_escape_string($conn, $title);
            $content = mysqli_real_escape_string($conn, $content);
            
            // Query UPDATE
            $query = "UPDATE posts 
                      SET title = '$title', 
                          content = '$content', 
                          category_id = $category_id 
                      WHERE id = $id";
            
            // Eksekusi query
            if (mysqli_query($conn, $query)) {
                $success = true;
                
                // Simpan pesan sukses ke session
                $_SESSION['message'] = "Artikel berhasil diperbarui!";
                $_SESSION['type'] = 'success';
                
                // Redirect ke halaman index dengan pesan sukses
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Jika ada error atau request bukan POST, kembali ke form edit
if (!$success) {
    if (!empty($errors)) {
        // Simpan error ke session untuk ditampilkan di form
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'category_id' => $_POST['category_id'] ?? ''
        ];
        
        // Redirect kembali ke form edit dengan id
        if (isset($_POST['id'])) {
            header("Location: edit.php?id=" . (int)$_POST['id']);
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        // Jika request bukan POST, redirect ke index
        header("Location: index.php");
        exit();
    }
}

// Tutup koneksi
mysqli_close($conn);
?>
