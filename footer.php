</main>

<footer class="footer mt-auto">
    <div class="container text-center">
        &copy; <?php echo date('Y'); ?> - Dibuat oleh <a href="index.php">SaiFoza</a>.
    </div>
</footer>

<!-- Pustaka JavaScript Utama -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<?php
// Memuat skrip khusus halaman jika ada
if (isset($page_scripts)) {
    echo $page_scripts;
}
?>

</body>
</html>
