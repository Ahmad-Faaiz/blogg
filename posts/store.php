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
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    
    // Validasi input
    if (empty($title)) {
        $errors[] = "Judul artikel tidak boleh kosong";
    }
    
    if (strlen($title) > 200) {
        $errors[] = "Judul artikel maksimal 200 karakter";
    }
    
    if (empty($content)) {
        $errors[] = "Konten artikel tidak boleh kosong";
    }
    
    if ($category_id <= 0) {
        $errors[] = "Kategori harus dipilih";
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        
        // Gunakan prepared statement untuk keamanan
        $query = "INSERT INTO posts (category_id, title, content, created_at) 
                  VALUES (?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (!$stmt) {
            $errors[] = "Error query: " . mysqli_error($conn);
        } else {
            // Bind parameter: i = integer, s = string
            mysqli_stmt_bind_param($stmt, "iss", $category_id, $title, $content);
            
            // Eksekusi query
            if (mysqli_stmt_execute($stmt)) {
                $success = true;
                
                // Dapatkan ID artikel yang baru dibuat
                $new_id = mysqli_insert_id($conn);
                
                // Tutup statement
                mysqli_stmt_close($stmt);
                
                // Redirect ke halaman index dengan pesan sukses
                $_SESSION['message'] = "Artikel berhasil dipublikasikan!";
                $_SESSION['type'] = 'success';
                
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Error: " . mysqli_error($conn);
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Jika ada error, kembali ke form create
if (!$success) {
    if (!empty($errors)) {
        // Simpan error ke session untuk ditampilkan di form
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'category_id' => $_POST['category_id'] ?? ''
        ];
    }
    
    // Redirect kembali ke form create
    header("Location: create.php");
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>
