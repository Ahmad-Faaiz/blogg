<?php
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi session
session_start();

// Mengecek apakah ada parameter id di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=" . urlencode("ID artikel tidak ditemukan"));
    exit();
}

$id = (int)$_GET['id'];

// Validasi id harus lebih besar dari 0
if ($id <= 0) {
    header("Location: index.php?error=" . urlencode("ID artikel tidak valid"));
    exit();
}

// Ambil data post dari database
$query = "SELECT * FROM posts WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php?error=" . urlencode("Artikel tidak ditemukan"));
    exit();
}

$post = mysqli_fetch_assoc($result);

// Ambil error dan form data dari session jika ada
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [
    'title' => $post['title'],
    'content' => $post['content'],
    'category_id' => $post['category_id']
];

// Hapus session setelah digunakan
unset($_SESSION['errors']);
unset($_SESSION['form_data']);

// Ambil semua kategori untuk dropdown
$cat_query = "SELECT id, name FROM categories ORDER BY name ASC";
$cat_result = mysqli_query($conn, $cat_query);
$categories = [];

if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container py-5">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <h1 class="fw-bold">
                <i class="bi bi-pencil-square"></i> Edit Tulisan
            </h1>
            <p class="text-muted">Perbarui artikel blog Anda</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    
                    <!-- Alert Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <h5 class="alert-heading">
                                <i class="bi bi-exclamation-circle"></i> Terjadi kesalahan!
                            </h5>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Info Post -->
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Dibuat:</strong> <?php echo date('d M Y H:i', strtotime($post['created_at'])); ?>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="update.php" id="form-edit-post">
                        
                        <!-- Hidden ID -->
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        
                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Judul Tulisan <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control form-control-lg <?php echo !empty($errors) && empty($form_data['title']) ? 'is-invalid' : ''; ?>" 
                                id="title" 
                                name="title" 
                                placeholder="Masukkan judul tulisan Anda" 
                                value="<?php echo htmlspecialchars($form_data['title']); ?>"
                                required
                                autofocus>
                            <small class="text-muted d-block mt-2">
                                💡 Buatlah judul yang menarik dan deskriptif
                            </small>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-4">
                            <label for="category_id" class="form-label fw-bold">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            
                            <?php if (empty($categories)): ?>
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Belum ada kategori. <a href="../categories/index.php" class="alert-link">Buat kategori terlebih dahulu</a>
                                </div>
                            <?php else: ?>
                                <select 
                                    class="form-select form-select-lg <?php echo !empty($errors) && empty($form_data['category_id']) ? 'is-invalid' : ''; ?>" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option 
                                            value="<?php echo $category['id']; ?>"
                                            <?php echo isset($form_data['category_id']) && $form_data['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <!-- Konten -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                Konten <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                class="form-control <?php echo !empty($errors) && empty($form_data['content']) ? 'is-invalid' : ''; ?>" 
                                id="content" 
                                name="content" 
                                rows="10" 
                                placeholder="Tuliskan konten artikel Anda di sini..." 
                                required><?php echo htmlspecialchars($form_data['content']); ?></textarea>
                            <small class="text-muted d-block mt-2">
                                💡 Gunakan paragraf yang jelas dan mudah dipahami
                            </small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<?php 
// Tutup koneksi
mysqli_close($conn);
include '../layout/footer.php'; 
?>
