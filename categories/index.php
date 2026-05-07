<?php 
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi variabel
$success_message = '';
$error_message = '';

// Proses tambah kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $category_name = isset($_POST['category_name']) ? trim($_POST['category_name']) : '';
    
    // Validasi
    if (empty($category_name)) {
        $error_message = "Nama kategori tidak boleh kosong!";
    } else {
        // Escape input
        $category_name = mysqli_real_escape_string($conn, $category_name);
        
        // Generate slug dari nama kategori
        $slug = strtolower(str_replace(' ', '-', $category_name));
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
        $slug = mysqli_real_escape_string($conn, $slug);
        
        // Query INSERT
        $query = "INSERT INTO categories (name, slug) 
                  VALUES ('$category_name', '$slug')";
        
        if (mysqli_query($conn, $query)) {
            $success_message = "Kategori berhasil ditambahkan!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Proses hapus kategori
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM categories WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        $success_message = "Kategori berhasil dihapus!";
    } else {
        $error_message = "Error: " . mysqli_error($conn);
    }
}

// Ambil semua kategori dari database
$query = "SELECT * FROM categories ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$categories = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container py-5">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold">
                <i class="bi bi-folder"></i> Manajemen Kategori
            </h1>
            <p class="text-muted">Kelola kategori blog Anda</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="../index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        
        <!-- Form Tambah Kategori -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Tambah Kategori Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="mb-3">
                            <label for="category_name" class="form-label fw-bold">Nama Kategori *</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="category_name" 
                                name="category_name" 
                                placeholder="Masukkan nama kategori" 
                                required>
                            <small class="text-muted d-block mt-2">
                                💡 Slug akan dibuat otomatis dari nama kategori
                            </small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus"></i> Tambah Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Kategori -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Kategori (<?php echo count($categories); ?>)</h5>
                </div>
                <div class="card-body">
                    
                    <?php if (empty($categories)): ?>
                        <div class="text-center py-5">
                            <p class="text-muted">
                                <i class="bi bi-inbox" style="font-size: 2rem;"></i><br>
                                Belum ada kategori. Silakan tambahkan kategori baru.
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Kategori</th>
                                        <th>Slug</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($categories as $category): 
                                    ?>
                                        <tr>
                                            <td><strong><?php echo $no++; ?></strong></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                            </td>
                                            <td>
                                                <code class="bg-light p-2 rounded">
                                                    <?php echo htmlspecialchars($category['slug']); ?>
                                                </code>
                                            </td>
                                            <td>
                                                <a href="delete.php?id=<?php echo $category['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Yakin ingin menghapus kategori ini? Posts yang terkait akan memiliki kategori kosong.')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

    </div>

</div>

<?php include '../layout/footer.php'; ?>

<?php mysqli_close($conn); ?>
