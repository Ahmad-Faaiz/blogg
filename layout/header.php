<!-- header.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Classic Blog</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts - Classic Serif -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Lora', serif;
        }

        body {
            background-color: #fefaf0;
            color: #2c3e50;
            line-height: 1.8;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            color: #1a1a1a;
            font-weight: 700;
        }

        /* Header Classic Style */
        .site-header {
            background-color: #f5f1eb;
            border-bottom: 3px solid #8b7355;
            padding: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #1a1a1a !important;
            font-family: 'Playfair Display', serif;
        }

        .navbar-brand:hover {
            color: #8b7355 !important;
            transition: color 0.3s ease;
        }

        /* Navigation Links */
        .navbar-nav .nav-link {
            color: #2c3e50 !important;
            margin: 0 15px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        .navbar-nav .nav-link:hover {
            color: #8b7355 !important;
            border-bottom: 2px solid #8b7355;
        }

        .navbar-nav .nav-link.active {
            color: #8b7355 !important;
            border-bottom: 2px solid #8b7355;
        }

        .navbar-toggler {
            border: 2px solid #8b7355;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(139, 115, 85, 0.25);
        }

        /* Classic Container */
        .container-fluid {
            background-color: #fefaf0;
        }

        /* Ornamental Line */
        .ornament {
            text-align: center;
            color: #c9b5a0;
            font-size: 24px;
            margin: 20px 0;
        }

        /* Button Style */
        .btn-classic {
            background-color: #8b7355;
            color: #fff;
            border: 2px solid #8b7355;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .btn-classic:hover {
            background-color: #6b5345;
            border-color: #6b5345;
            color: #fff;
        }

        .btn-classic-outline {
            background-color: transparent;
            color: #8b7355;
            border: 2px solid #8b7355;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .btn-classic-outline:hover {
            background-color: #8b7355;
            color: #fff;
        }

        /* Hero Section */
        .hero-classic {
            background: linear-gradient(135deg, #f5f1eb 0%, #e8dfd5 100%);
            border: 2px solid #c9b5a0;
            padding: 60px 40px;
            text-align: center;
            position: relative;
        }

        .hero-classic::before {
            content: "✦";
            display: block;
            font-size: 28px;
            color: #8b7355;
            margin-bottom: 20px;
        }

        .hero-classic h1 {
            font-size: 48px;
            color: #1a1a1a;
            margin-bottom: 20px;
            letter-spacing: 2px;
        }

        .hero-classic p {
            font-size: 18px;
            color: #5a4a3a;
            max-width: 600px;
            margin: 0 auto 30px;
            font-style: italic;
        }

        /* Card Classic */
        .card-classic {
            border: 1px solid #c9b5a0;
            border-radius: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            background-color: #fffef9;
        }

        .card-classic:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.12);
            border-color: #8b7355;
        }

        .card-classic-header {
            background-color: #f5f1eb;
            border-bottom: 2px solid #c9b5a0;
            padding: 15px 20px;
        }

        .card-classic-header h5 {
            margin: 0;
            font-size: 16px;
            color: #8b7355;
        }

        .card-classic-body {
            padding: 25px;
        }

        /* Category Badge Classic */
        .category-badge {
            display: inline-block;
            background-color: #c9b5a0;
            color: #fff;
            padding: 6px 14px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 0;
            margin-bottom: 12px;
        }

        /* Article Meta */
        .article-meta {
            color: #8b7355;
            font-size: 13px;
            font-style: italic;
            margin-bottom: 15px;
        }

        /* Sidebar */
        .sidebar-classic {
            background-color: #f5f1eb;
            border: 1px solid #c9b5a0;
            padding: 25px;
            border-radius: 0;
        }

        .sidebar-classic h5 {
            font-size: 18px;
            color: #1a1a1a;
            border-bottom: 2px solid #8b7355;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .sidebar-classic ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-classic li {
            padding: 10px 0;
            border-bottom: 1px dotted #c9b5a0;
        }

        .sidebar-classic li:last-child {
            border-bottom: none;
        }

        .sidebar-classic a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
        }

        .sidebar-classic a:hover {
            color: #8b7355;
        }

        /* Footer Classic */
        footer {
            background-color: #2c3e50;
            color: #c9b5a0;
            border-top: 3px solid #8b7355;
            padding: 40px 0;
        }

        footer h5 {
            color: #f5f1eb;
            font-size: 16px;
            margin-bottom: 15px;
        }

        footer a {
            color: #c9b5a0;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: #fff;
        }

        footer p {
            font-size: 13px;
        }

        hr {
            border-color: #c9b5a0;
        }

        /* Separator */
        .separator {
            height: 2px;
            background: linear-gradient(to right, transparent, #8b7355, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>

<!-- Header Classic -->
<header class="site-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo / Judul -->
            <a class="navbar-brand" href="index.php">
                CLASSIC BLOG
            </a>

            <!-- Tombol Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Navigasi -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentang.php">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kontak.php">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Konten dimulai -->
<div class="container-fluid">