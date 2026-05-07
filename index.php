<?php
// Mengambil koneksi database
require_once 'config/database.php';

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query untuk menghitung total posts
$count_query = "SELECT COUNT(*) as total FROM posts";
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_posts = $count_row['total'];
$total_pages = ceil($total_posts / $limit);

// Query untuk mengambil posts dengan kategori (newest first)
$query = "SELECT 
            p.id,
            p.title,
            p.content,
            p.created_at,
            p.category_id,
            c.name as category_name,
            c.slug as category_slug
          FROM posts p
          LEFT JOIN categories c ON p.category_id = c.id
          ORDER BY p.created_at DESC
          LIMIT $limit OFFSET $offset";

$result = mysqli_query($conn, $query);
$posts = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $posts[] = $row;
    }
}

// Ambil kategori untuk sidebar
$cat_query = "SELECT id, name, slug FROM categories ORDER BY name ASC LIMIT 10";
$cat_result = mysqli_query($conn, $cat_query);
$categories = [];

if ($cat_result) {
    while ($row = mysqli_fetch_assoc($cat_result)) {
        $categories[] = $row;
    }
}
?>

<?php include 'layout/header.php'; ?>

<div class="container-fluid py-5 bg-light">
    
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="bg-primary text-white rounded-4 shadow p-5">
                <div class="text-center">
                    <h1 class="display-4 fw-bold">
                        <i class="bi bi-book"></i> My Blog Platform
                    </h1>
                    <p class="lead mt-3">
                        Temukan artikel menarik tentang teknologi, programming, dan web development.
                    </p>
                    <div class="mt-4">
                        <a href="categories/index.php" class="btn btn-warning btn-lg me-2">
                            <i class="bi bi-tag"></i> Kelola Kategori
                        </a>
                        <a href="posts/index.php" class="btn btn-light btn-lg">
                            <i class="bi bi-newspaper"></i> Dashboard Artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        
        <!-- Main Content -->
        <div class="row">
            
            <!-- Posts Grid -->
            <div class="col-md-8">
                
                <!-- Articles Header -->
                <div class="mb-4">
                    <h2 class="fw-bold">
                        <i class="bi bi-newspaper"></i> Artikel Terbaru
                    </h2>
                    <p class="text-muted">Total: <strong><?php echo $total_posts; ?></strong> artikel</p>
                </div>

                <!-- Articles List -->
                <?php if (empty($posts)): ?>
                    
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">
                            <i class="bi bi-inbox text-muted"></i>
                        </div>
                        <h4 class="text-muted">Belum Ada Artikel</h4>
                        <p class="text-muted">Artikel akan muncul di sini.</p>
                    </div>

                <?php else: ?>

                    <div class="row">
                        <?php foreach ($posts as $post): 
                            $content_preview = strlen($post['content']) > 150 
                                ? substr(strip_tags($post['content']), 0, 150) . '...' 
                                : strip_tags($post['content']);
                        ?>
                            <!-- Article Card -->
                            <div class="col-md-6 mb-4">
                                <div class="card shadow-sm border-0 h-100 hover-card" style="transition: all 0.3s ease;">
                                    
                                    <!-- Card Image -->
                                    <div class="position-relative overflow-hidden" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="bi bi-image text-white" style="font-size: 3rem; opacity: 0.5;"></i>
                                        </div>
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        
                                        <!-- Category Badge -->
                                        <?php if (!empty($post['category_name'])): ?>
                                            <div class="mb-2">
                                                <span class="badge bg-primary">
                                                    <?php echo htmlspecialchars($post['category_name']); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Title -->
                                        <h5 class="card-title fw-bold">
                                            <?php echo htmlspecialchars($post['title']); ?>
                                        </h5>

                                        <!-- Content Preview -->
                                        <p class="card-text text-muted flex-grow-1">
                                            <?php echo htmlspecialchars($content_preview); ?>
                                        </p>

                                        <!-- Meta -->
                                        <small class="text-muted d-block mb-3">
                                            <i class="bi bi-calendar-event"></i>
                                            <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                        </small>

                                        <!-- Read More Button -->
                                        <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-right"></i> Baca Selengkapnya
                                        </a>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-5">
                            <ul class="pagination justify-content-center">
                                <!-- Previous -->
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo max(1, $page - 1); ?>">
                                        <i class="bi bi-chevron-left"></i> Sebelumnya
                                    </a>
                                </li>

                                <!-- Page Numbers -->
                                <?php
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);

                                if ($start_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=1">1</a>
                                    </li>
                                    <?php if ($start_page > 2): ?>
                                        <li class="page-item disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif;
                                endif;

                                for ($i = $start_page; $i <= $end_page; $i++): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>">
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
                                        <a class="page-link" href="?page=<?php echo $total_pages; ?>">
                                            <?php echo $total_pages; ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Next -->
                                <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo min($total_pages, $page + 1); ?>">
                                        Selanjutnya <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>

                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                
                <!-- Categories Widget -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-tag"></i> Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted text-center">Belum ada kategori</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($categories as $cat): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><?php echo htmlspecialchars($cat['name']); ?></span>
                                        <span class="badge bg-primary rounded-pill">
                                            <?php 
                                            // Count posts dalam kategori
                                            $count_q = "SELECT COUNT(*) as cnt FROM posts WHERE category_id = " . $cat['id'];
                                            $count_r = mysqli_query($conn, $count_q);
                                            $count_d = mysqli_fetch_assoc($count_r);
                                            echo $count_d['cnt'];
                                            ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Links Widget -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-lightning"></i> Akses Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="posts/index.php" class="btn btn-outline-primary">
                                <i class="bi bi-newspaper"></i> Dashboard
                            </a>
                            <a href="posts/create.php" class="btn btn-outline-success">
                                <i class="bi bi-plus-circle"></i> Tambah Artikel
                            </a>
                            <a href="categories/index.php" class="btn btn-outline-warning">
                                <i class="bi bi-tag"></i> Kelola Kategori
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- CSS for hover effect -->
<style>
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.3) !important;
    }
</style>

<?php 
// Tutup koneksi
mysqli_close($conn);
include 'layout/footer.php'; 
?>