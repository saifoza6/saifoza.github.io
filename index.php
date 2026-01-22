<?php 
$pageTitle = 'Saiul.Com - Media Downloader';
include 'header.php'; 
?>

<div class="container">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Media Downloader</h1>
        <p class="lead text-muted">Pilih layanan yang ingin Anda gunakan di bawah ini.</p>
    </div>
    
    <div class="row g-4 justify-content-center">
        <!-- TikTok Downloader -->
        <div class="col-6 col-lg-3">
            <a href="tiktok.php" class="card text-decoration-none h-100 p-4 text-center transition-hover">
                <i class="fab fa-tiktok fa-3x mb-3" style="color: #000;"></i>
                <h5 class="fw-bold mb-1">TikTok</h5>
                <p class="text-muted small">Video & Slideshow</p>
            </a>
        </div>
        
        <!-- YouTube Downloader -->
        <div class="col-6 col-lg-3">
            <a href="youtube.php" class="card text-decoration-none h-100 p-4 text-center transition-hover">
                <i class="fab fa-youtube fa-3x mb-3 text-danger"></i>
                <h5 class="fw-bold mb-1">YouTube</h5>
                <p class="text-muted small">Video & MP3</p>
            </a>
        </div>

        <!-- Facebook Downloader (Coming Soon) -->
        <div class="col-6 col-lg-3">
            <div class="card h-100 p-4 text-center opacity-50">
                <i class="fab fa-facebook fa-3x mb-3" style="color: #1877F2;"></i>
                <h5 class="fw-bold mb-1">Facebook</h5>
                <span class="badge bg-secondary">Coming Soon</span>
            </div>
        </div>

        <!-- Instagram Downloader (Coming Soon) -->
        <div class="col-6 col-lg-3">
             <div class="card h-100 p-4 text-center opacity-50">
                <i class="fab fa-instagram fa-3x mb-3" style="color: #E4405F;"></i>
                <h5 class="fw-bold mb-1">Instagram</h5>
                <span class="badge bg-secondary">Coming Soon</span>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
</style>

<?php include 'footer.php'; ?>
