<?php
// Mengambil koneksi database
require_once 'config/database.php';

// Inisialisasi session
session_start();

// Validasi koneksi database
if (!$conn) {
    header("Location: index.php?error=" . urlencode("Kesalahan koneksi database"));
    exit();
}

// Mengecek apakah ada parameter id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?error=" . urlencode("Artikel tidak ditemukan"));
    exit();
}

$id = (int)$_GET['id'];

// Validasi id
if ($id <= 0) {
    header("Location: index.php?error=" . urlencode("ID artikel tidak valid"));
    exit();
}

// Query menggunakan prepared statement untuk keamanan
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
          WHERE p.id = ?
          LIMIT 1";

$stmt = mysqli_prepare($conn, $query);

if (!$stmt) {
    header("Location: index.php?error=" . urlencode("Kesalahan query database"));
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Cek apakah post ada
if (!$result || mysqli_num_rows($result) === 0) {
    mysqli_stmt_close($stmt);
    header("Location: index.php?error=" . urlencode("Artikel tidak ditemukan"));
    exit();
}

$post = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Query untuk mendapatkan artikel related (kategori yang sama)
$related_query = "SELECT 
                   p.id,
                   p.title,
                   p.created_at
                 FROM posts p
                 WHERE p.category_id = ? 
                 AND p.id != ?
                 ORDER BY p.created_at DESC
                 LIMIT 3";

$stmt_related = mysqli_prepare($conn, $related_query);

if ($stmt_related) {
    mysqli_stmt_bind_param($stmt_related, "ii", $post['category_id'], $id);
    mysqli_stmt_execute($stmt_related);
    $related_result = mysqli_stmt_get_result($stmt_related);
    $related_posts = [];
    
    while ($row = mysqli_fetch_assoc($related_result)) {
        $related_posts[] = $row;
    }
    
    mysqli_stmt_close($stmt_related);
} else {
    $related_posts = [];
}
?>

<?php include 'layout/header.php'; ?>

<div class="container py-5">
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php" class="text-decoration-none">
                    <i class="bi bi-house"></i> Home
                </a>
            </li>
            <?php if (!empty($post['category_name'])): ?>
                <li class="breadcrumb-item">
                    <a href="index.php" class="text-decoration-none">
                        <?php echo htmlspecialchars($post['category_name']); ?>
                    </a>
                </li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo htmlspecialchars($post['title']); ?>
            </li>
        </ol>
    </nav>

    <div class="row">
        
        <!-- Main Content -->
        <div class="col-md-8">
            
            <!-- Article Header -->
            <article class="mb-5">
                
                <!-- Header Info -->
                <div class="mb-4 pb-4 border-bottom">
                    <h1 class="fw-bold mb-3">
                        <?php echo htmlspecialchars($post['title']); ?>
                    </h1>

                    <div class="d-flex gap-3 align-items-center flex-wrap">
                        <!-- Category -->
                        <?php if (!empty($post['category_name'])): ?>
                            <span class="badge bg-primary p-2">
                                <i class="bi bi-tag"></i>
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </span>
                        <?php endif; ?>

                        <!-- Date -->
                        <small class="text-muted">
                            <i class="bi bi-calendar-event"></i>
                            <?php echo date('d F Y', strtotime($post['created_at'])); ?>
                        </small>

                        <!-- Time -->
                        <small class="text-muted">
                            <i class="bi bi-clock"></i>
                            <?php echo date('H:i', strtotime($post['created_at'])); ?> WIB
                        </small>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="mb-4">
                    <div class="position-relative overflow-hidden rounded-3" style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-size: cover; background-position: center;">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-image text-white" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="article-content mb-5">
                    <div class="fs-5 lh-lg">
                        <?php 
                        // Format paragraf konten dengan baik
                        $content = htmlspecialchars($post['content']);
                        $paragraphs = array_filter(explode("\n\n", $content));
                        
                        foreach ($paragraphs as $paragraph) {
                            $paragraph = trim($paragraph);
                            if (!empty($paragraph)) {
                                echo "<p class='mb-3'>" . nl2br($paragraph) . "</p>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Article Footer -->
                <div class="d-flex gap-2 pt-4 border-top flex-wrap">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

            </article>

        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            
            <!-- Related Articles -->
            <?php if (!empty($related_posts)): ?>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-bookmark"></i> Artikel Terkait
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach ($related_posts as $related): ?>
                                <li class="list-group-item">
                                    <a href="post.php?id=<?php echo $related['id']; ?>" class="text-decoration-none text-dark fw-500 d-block mb-2">
                                        <?php echo htmlspecialchars($related['title']); ?>
                                    </a>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        <?php echo date('d M Y', strtotime($related['created_at'])); ?>
                                    </small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Quick Navigation -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Navigasi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i> Beranda
                        </a>
                        <a href="posts/index.php" class="btn btn-outline-success">
                            <i class="bi bi-newspaper"></i> Dashboard
                        </a>
                        <a href="posts/create.php" class="btn btn-outline-info">
                            <i class="bi bi-plus-circle"></i> Buat Artikel
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<?php 
// Tutup koneksi
mysqli_close($conn);
include 'layout/footer.php'; 
?>