<?php
// Mengambil koneksi database
require_once '../config/database.php';

// Inisialisasi session
session_start();

// Ambil message dari session jika ada
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$type = isset($_SESSION['type']) ? $_SESSION['type'] : 'info';

// Hapus session setelah digunakan
unset($_SESSION['message']);
unset($_SESSION['type']);

// Inisialisasi variabel untuk search dan filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_escaped = mysqli_real_escape_string($conn, $search);

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total posts
$count_query = "SELECT COUNT(*) as total FROM posts WHERE title LIKE '%$search_escaped%'";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_posts = $count_row['total'];
$total_pages = ceil($total_posts / $limit);

// Query untuk mengambil posts dengan kategori
$query = "SELECT 
            p.id,
            p.title,
            p.created_at,
            p.category_id,
            c.name as category_name
          FROM posts p
          LEFT JOIN categories c ON p.category_id = c.id
          WHERE p.title LIKE '%$search_escaped%'
          ORDER BY p.created_at DESC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$posts = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container-fluid py-5">
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="fw-bold">
                <i class="bi bi-newspaper"></i> Dashboard Artikel
            </h1>
            <p class="text-muted">Kelola semua artikel blog Anda</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="create.php" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Buat Artikel Baru
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $type === 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-<?php echo $type === 'error' ? 'exclamation-circle' : 'check-circle'; ?>"></i>
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Search Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" action="" class="d-flex gap-2">
                <input 
                    type="text" 
                    class="form-control" 
                    name="search" 
                    placeholder="Cari artikel..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-search"></i> Cari
                </button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                <i class="bi bi-list-check"></i> Total: <?php echo $total_posts; ?> artikel
            </span>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-responsive">
        <div class="card shadow-sm border-0">
            
            <?php if (empty($posts)): ?>
                <!-- Empty State -->
                <div class="card-body p-5 text-center">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h4 class="text-muted">Belum Ada Artikel</h4>
                    <p class="text-muted">Mulai buat artikel pertama Anda sekarang.</p>
                    <a href="create.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Artikel Baru
                    </a>
                </div>
            <?php else: ?>
                <!-- Table -->
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 40%;">Judul</th>
                            <th style="width: 20%;">Kategori</th>
                            <th style="width: 20%;">Tanggal</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = $offset + 1;
                        foreach ($posts as $post): 
                        ?>
                            <tr>
                                <td>
                                    <strong><?php echo $no++; ?></strong>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($post['category_name'])): ?>
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Tanpa Kategori
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d M Y H:i', strtotime($post['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit.php?id=<?php echo $post['id']; ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?php echo $post['id']; ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Hapus"
                                           onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <!-- Previous Page -->
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>&search=<?php echo urlencode($search); ?>">
                        <i class="bi bi-chevron-left"></i> Sebelumnya
                    </a>
                </li>

                <!-- Page Numbers -->
                <?php
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">1</a>
                    </li>
                    <?php if ($start_page > 2): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif;
                endif;

                for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor;

                if ($end_page < $total_pages): ?>
                    <?php if ($end_page < $total_pages - 1): ?>
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>">
                            <?php echo $total_pages; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Next Page -->
                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>&search=<?php echo urlencode($search); ?>">
                        Selanjutnya <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

</div>

<?php 
// Tutup koneksi
mysqli_close($conn);
include '../layout/footer.php'; 
?>
