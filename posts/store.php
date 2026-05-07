<?php
// Mengambil koneksi database
require_once '../config/database.php';

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
    
    if (empty($content)) {
        $errors[] = "Konten artikel tidak boleh kosong";
    }
    
    if ($category_id <= 0) {
        $errors[] = "Kategori harus dipilih";
    }
    
    // Jika tidak ada error, simpan ke database
    if (empty($errors)) {
        
        // Escape input untuk keamanan
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);
        $created_at = date('Y-m-d H:i:s');
        
        // Query INSERT ke tabel posts
        $query = "INSERT INTO posts (category_id, title, content, created_at) 
                  VALUES ($category_id, '$title', '$content', '$created_at')";
        
        // Eksekusi query
        if (mysqli_query($conn, $query)) {
            $success = true;
            
            // Redirect ke halaman index dengan pesan sukses
            header("Location: index.php?message=Post berhasil ditambahkan&type=success");
            exit();
        } else {
            $errors[] = "Error: " . mysqli_error($conn);
        }
    }
}

// Jika ada error atau request bukan POST, kembali ke form create
if (!$success) {
    if (!empty($errors)) {
        // Simpan error ke session untuk ditampilkan di form
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
                'category_id' => $_POST['category_id'] ?? ''

// Tutup koneksi
mysqli_close($conn);
?>
