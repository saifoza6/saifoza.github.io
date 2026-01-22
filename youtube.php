<?php
// BAGIAN LOGIKA PHP (API & Proxy Download)

// Fungsi untuk mendeteksi apakah request datang dari AJAX
function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

set_time_limit(0);

// Bagian 1: Proxy untuk menangani unduhan file secara langsung
if (isset($_GET['download_url']) && isset($_GET['filename'])) {
    $fileUrl = trim($_GET['download_url']);
    $fileName = basename(trim($_GET['filename']));
    if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) { http_response_code(400); die("URL file tidak valid."); }
    $headers = get_headers($fileUrl, 1);
    if (!$headers || strpos($headers[0], '200') === false) { http_response_code(404); die("File tidak ditemukan."); }
    header("Content-Description: File Transfer");
    header("Content-Type: " . ($headers['Content-Type'] ?? 'application/octet-stream'));
    header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
    header("Content-Transfer-Encoding: binary");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    if (isset($headers['Content-Length'])) { header("Content-Length: " . $headers['Content-Length']); }
    if (ob_get_level()) ob_end_clean();
    readfile($fileUrl);
    exit;
}

// Bagian 2: API endpoint HANYA untuk request AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_ajax_request()) {
    header('Content-Type: application/json');
    $url = trim($_POST['url']);
    if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/(youtube.com|youtu.be)/', $url)) {
        echo json_encode(['status' => false, 'error' => 'URL YouTube tidak valid.']);
        exit;
    }
    $apiKey = 'HAMS-2faccea41094'; // Ganti dengan API Key Anda yang valid

    function fetchApiData($apiUrl) {
        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 45, CURLOPT_FOLLOWLOCATION => true]);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }
    
    $dataMp4 = fetchApiData("https://api.hamsoffc.me/download/ytmp4?apikey={$apiKey}&url=" . urlencode($url));
    $dataMp3 = fetchApiData("https://api.hamsoffc.me/download/ytmp3?apikey={$apiKey}&url=" . urlencode($url));

    if (empty($dataMp4['result']['url']) || empty($dataMp3['result']['url'])) {
        echo json_encode(['status' => false, 'error' => 'Gagal mengambil link unduhan. API mungkin sibuk.']);
        exit;
    }
    
    echo json_encode([
        'status'    => true,
        'data'      => [
            'title'     => $dataMp4['result']['judul'] ?? 'Tidak ada judul',
            'thumbnail' => $dataMp4['result']['thumbnail'] ?? '',
            'duration'  => $dataMp4['result']['durasi'] ?? 'N/A',
            'mp4_url'   => $dataMp4['result']['url'],
            'mp3_url'   => $dataMp3['result']['url']
        ]
    ]);
    exit;
}

// BAGIAN KONTEN HALAMAN
$pageTitle = 'Saiul.Com - YouTube Downloader';
include 'header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 p-lg-5">
                 <div class="text-center mb-4">
                    <i class="fab fa-youtube fa-3x mb-3 text-danger"></i>
                    <h1 class="h2 fw-bold">YouTube Downloader</h1>
                    <p class="text-muted">Unduh video favoritmu dalam format MP4 atau MP3.</p>
                </div>
                <form id="youtube-form" onsubmit="return false;">
                    <div class="input-group input-group-lg mb-3 shadow-sm" style="border-radius: 50px; overflow: hidden;">
                        <input type="url" id="url" name="url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." required style="border: none; padding-left: 1.5rem;">
                        <button type="submit" id="submit-btn" class="btn btn-gradient d-flex align-items-center justify-content-center px-4" style="gap: 0.5rem;">
                            <span id="btn-text">Cari</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
                
                <div id="result-container" class="mt-4"></div>
                
                <div class="mt-5 p-4 rounded-3" style="background-color: #f8f9fa;">
                    <h3 class="h5 fw-bold mb-3"><i class="fas fa-book-open me-2 text-primary"></i>Panduan Penggunaan Lengkap</h3>
                    
                    <h6 class="fw-bold mt-4">Langkah 1: Salin Tautan Video YouTube</h6>
                    <p class="small">Anda bisa mendapatkan tautan dari aplikasi YouTube di HP atau dari browser di komputer.</p>

                    <div class="row">
                        <div class="col-md-6">
                             <strong><i class="fas fa-mobile-alt me-1"></i> Untuk Pengguna HP (Aplikasi YouTube):</strong>
                             <ol class="small ps-4 mt-2">
                                <li>Buka aplikasi YouTube dan putar video yang Anda suka.</li>
                                <li>Di bawah pemutar video, ketuk tombol <strong>"Bagikan"</strong> <i class="fas fa-share"></i>.</li>
                                <li>Pada menu yang tampil, pilih opsi <strong>"Salin link"</strong> <i class="fas fa-copy"></i>.</li>
                             </ol>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-desktop me-1"></i> Untuk Pengguna Komputer (Situs Web):</strong>
                            <ol class="small ps-4 mt-2">
                                <li>Buka situs `youtube.com` di browser Anda.</li>
                                <li>Buka halaman video yang ingin diunduh.</li>
                                <li>Salin seluruh URL yang ada di <strong>bilah alamat (address bar)</strong> browser Anda.</li>
                            </ol>
                        </div>
                    </div>
                     
                    <h6 class="fw-bold mt-4">Langkah 2: Proses Unduhan</h6>
                     <ol class="small ps-4 mt-2" start="4">
                        <li>Kembali ke halaman ini.</li>
                        <li><strong>Tempel (paste) tautan</strong> yang sudah disalin ke dalam kolom di atas.</li>
                        <li>Klik tombol <strong>"Cari"</strong>. Pratinjau video, judul, dan durasi akan dimuat.</li>
                        <li>Pilih format unduhan yang Anda inginkan:
                            <ul>
                                <li><strong>.MP4:</strong> Untuk mengunduh video beserta suaranya.</li>
                                <li><strong>.MP3:</strong> Untuk mengunduh audionya saja.</li>
                            </ul>
                        </li>
                     </ol>
                     <p class="mt-3 small text-muted fst-italic"><strong>Catatan:</strong> Layanan ini ditujukan untuk penggunaan pribadi. Pastikan Anda memiliki hak atau izin untuk mengunduh dan menggunakan konten dari YouTube.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('youtube-form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const spinner = document.getElementById('spinner');
        const resultContainer = document.getElementById('result-container');
        
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const url = document.getElementById('url').value.trim();
            if (!url) return;

            setLoading(true);
            resultContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            const formData = new FormData();
            formData.append('url', url);

            fetch('youtube.php', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(response => {
                if (response.status && response.data) {
                    displayResult(response.data);
                } else {
                    displayError(response.error || 'Gagal memproses permintaan.');
                }
            })
            .catch(err => displayError('Gagal terhubung ke server.'))
            .finally(() => setLoading(false));
        });

        function setLoading(isLoading) {
            btnText.classList.toggle('d-none', isLoading);
            spinner.classList.toggle('d-none', !isLoading);
            submitBtn.disabled = isLoading;
            resultContainer.style.opacity = isLoading ? '0.5' : '1';
        }

        function displayError(message) {
            resultContainer.innerHTML = `<div class="alert alert-danger mt-4"><i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal:</strong> ${escapeHtml(message)}</div>`;
        }

        function displayResult(data) {
            const safeTitle = (data.title || 'yt-video').replace(/[^a-z0-9_-]/gi, '_').substring(0, 60);
            const mp4Url = `?download_url=${encodeURIComponent(data.mp4_url)}&filename=${encodeURIComponent(safeTitle + '.mp4')}`;
            const mp3Url = `?download_url=${encodeURIComponent(data.mp3_url)}&filename=${encodeURIComponent(safeTitle + '.mp3')}`;
            
            // Perubahan di baris ini: d-sm-flex menjadi d-flex dan justify-content-center ditambahkan
            const resultHtml = `
            <div class="p-4 rounded-3 mt-4" style="background: #f8f9fa;">
                <div class="row g-4 align-items-center">
                    <div class="col-md-5">
                        <img src="${escapeHtml(data.thumbnail)}" alt="Thumbnail" class="img-fluid rounded-3 shadow-lg">
                    </div>
                    <div class="col-md-7 text-center">
                        <h3 class="h5 fw-bold">${escapeHtml(data.title)}</h3>
                        <p class="text-muted small mb-3"><i class="far fa-clock"></i> Durasi: ${escapeHtml(data.duration)}</p>
                        <div class="d-flex justify-content-center flex-wrap gap-2">
                            <a href="${mp3Url}" class="btn btn-warning rounded-pill shadow-sm text-dark px-3"><i class="fas fa-music me-2"></i>Audio (.mp3)</a>
                          <a href="${mp4Url}" class="btn btn-success rounded-pill shadow-sm px-3"><i class="fas fa-video me-2"></i>Video (.mp4)</a>
                        </div>
                    </div>
                </div>
            </div>`;
            resultContainer.innerHTML = resultHtml;
        }

        function escapeHtml(unsafe) {
            return (unsafe || "").replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'})[m]);
        }
    });
</script>

<?php include 'footer.php'; ?>
