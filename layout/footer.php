<!-- footer.php -->

</div> <!-- Penutup container dari header.php -->

<!-- Footer -->
<footer class="bg-dark text-white mt-5 pt-4 pb-3">
    <div class="container">

        <div class="row">

            <!-- Tentang -->
            <div class="col-md-4 mb-3">
                <h5>MyWebsite</h5>
                <p>
                    Website sederhana menggunakan PHP dan Bootstrap 5
                    untuk tampilan modern dan responsive.
                </p>
            </div>

            <!-- Navigasi -->
            <div class="col-md-4 mb-3">
                <h5>Menu</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="index.php" class="text-white text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="produk.php" class="text-white text-decoration-none">
                            Produk
                        </a>
                    </li>

                    <li>
                        <a href="tentang.php" class="text-white text-decoration-none">
                            Tentang
                        </a>
                    </li>

                    <li>
                        <a href="kontak.php" class="text-white text-decoration-none">
                            Kontak
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Sosial Media -->
            <div class="col-md-4 mb-3">
                <h5>Sosial Media</h5>

                <a href="#" class="text-white me-3 fs-4">
                    <i class="bi bi-facebook"></i>
                </a>

                <a href="#" class="text-white me-3 fs-4">
                    <i class="bi bi-instagram"></i>
                </a>

                <a href="#" class="text-white fs-4">
                    <i class="bi bi-youtube"></i>
                </a>
            </div>

        </div>

        <hr class="border-light">

        <!-- Copyright -->
        <div class="text-center">
            <p class="mb-0">
                &copy; <?php echo date('Y'); ?> MyWebsite.
                All Rights Reserved.
            </p>
        </div>

    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>