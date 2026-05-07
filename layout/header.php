<!-- header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistem Website</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 22px;
        }

        .nav-link {
            transition: 0.3s;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">

        <!-- Logo / Judul -->
        <a class="navbar-brand" href="index.php">
            <i class="bi bi-shop"></i> MyWebsite
        </a>

        <!-- Tombol Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Navigasi -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="produk.php">
                        <i class="bi bi-box-seam"></i> Produk
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="tentang.php">
                        <i class="bi bi-info-circle"></i> Tentang
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="kontak.php">
                        <i class="bi bi-telephone"></i> Kontak
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link btn btn-warning text-dark ms-lg-3 px-3" href="login.php">
                        Login
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- Konten dimulai -->
<div class="container mt-4">