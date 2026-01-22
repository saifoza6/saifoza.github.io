<?php
// BAGIAN LOGIKA PHP (API & Proxy Download)
set_time_limit(0);
if (isset($_GET['download_url']) && isset($_GET['filename'])) {
    $fileUrl = trim($_GET['download_url']);
    $fileName = basename(trim($_GET['filename']));
    if (!filter_var($fileUrl, FILTER_VALIDATE_URL)) { http_response_code(400); die("URL file tidak valid."); }
    $headers = get_headers($fileUrl, 1);
    if (!$headers || strpos($headers[0], '200') === false) { http_response_code(404); die("File tidak ditemukan."); }
    header("Content-Description: File Transfer");
    header("Content-Type: " . ($headers['Content-Type'] ?? 'application/octet-stream'));
    header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
    header("Expires: 0");
    header("Cache-Control: must-revalidate");
    header("Pragma: public");
    if (isset($headers['Content-Length'])) { header("Content-Length: " . $headers['Content-Length']); }
    if (ob_get_level()) ob_end_clean();
    readfile($fileUrl);
    exit;
}
if (isset($_GET['url']) && !isset($_GET['download_url'])) {
    header('Content-Type: application/json');
    $tiktok_url = trim($_GET['url']);
    if (empty($tiktok_url)) { http_response_code(400); echo json_encode(['error' => 'URL tidak boleh kosong.']); exit; }
    function fetch_tiktok_data($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/tiktok\.com/', $url)) { return ['code' => -1, 'msg' => 'URL TikTok tidak valid.']; }
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://www.tikwm.com/api/',
            CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true, CURLOPT_POSTFIELDS => http_build_query(['url' => $url]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded; charset=UTF-8', 'User-Agent: Mozilla/5.0'],
            CURLOPT_TIMEOUT => 20
        ]);
        $response = curl_exec($ch); $error = curl_error($ch); curl_close($ch);
        if ($error) return ['code' => -1, 'msg' => 'Gagal terhubung ke API: ' . $error];
        return json_decode($response, true) ?? ['code' => -1, 'msg' => 'Respons API tidak valid.'];
    }
    $result = fetch_tiktok_data($tiktok_url);
    if (isset($result['code']) && $result['code'] !== 0) { http_response_code(400); echo json_encode(['error' => $result['msg'] ?? 'Gagal mengambil data.']); }
    else { echo json_encode($result); }
    exit;
}

// BAGIAN KONTEN HALAMAN
$pageTitle = 'Saiul.Com - TikTok Downloader';
include 'header.php'; 
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card p-4 p-lg-5">
                <div class="text-center mb-4">
                    <i class="fab fa-tiktok fa-3x mb-3" style="color: #333;"></i>
                    <h1 class="h2 fw-bold">TikTok Downloader</h1>
                    <p class="text-muted">Tempel link video atau slideshow untuk memulai.</p>
                </div>
                <form id="tiktok-form" onsubmit="return false;">
                    <div class="input-group input-group-lg mb-3 shadow-sm" style="border-radius: 50px; overflow: hidden;">
                        <input type="url" id="url" name="url" class="form-control" placeholder="https://www.tiktok.com/@user/video/..." required style="border: none; padding-left: 1.5rem;">
                        <button type="submit" id="submit-btn" class="btn btn-gradient d-flex align-items-center justify-content-center px-4" style="gap: 0.5rem;">
                            <span id="btn-text">Cari</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </div>
                </form>
                
                <div id="result-container" class="mt-4"></div>

                <div class="mt-5 p-4 rounded-3" style="background-color: #f8f9fa;">
                    <h3 class="h5 fw-bold mb-3"><i class="fas fa-book-open me-2 text-primary"></i>Panduan Penggunaan Lengkap</h3>
                    
                    <h6 class="fw-bold mt-4">Langkah 1: Temukan dan Salin Tautan Video TikTok</h6>
                    <p class="small">Proses penyalinan tautan sedikit berbeda tergantung perangkat yang Anda gunakan.</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-mobile-alt me-1"></i> Untuk Pengguna HP (Aplikasi TikTok):</strong>
                            <ol class="small ps-4 mt-2">
                                <li>Buka aplikasi TikTok dan cari video yang Anda inginkan.</li>
                                <li>Ketuk tombol <strong>"Bagikan"</strong> (ikon panah melengkung di sisi kanan).</li>
                                <li>Dari menu yang muncul, geser ke samping dan pilih <strong>"Salin tautan"</strong> <i class="fas fa-copy"></i>.</li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-desktop me-1"></i> Untuk Pengguna Komputer (Situs Web):</strong>
                             <ol class="small ps-4 mt-2">
                                <li>Buka situs `tiktok.com` di browser Anda.</li>
                                <li>Arahkan ke video yang diinginkan.</li>
                                <li>Klik tombol <strong>"Salin tautan"</strong> yang berada di sebelah informasi video.</li>
                            </ol>
                        </div>
                    </div>

                    <h6 class="fw-bold mt-4">Langkah 2: Tempel Tautan dan Unduh</h6>
                    <ol class="small ps-4 mt-2" start="4">
                        <li>Kembali ke halaman ini.</li>
                        <li><strong>Tempel (paste) tautan</strong> yang sudah Anda salin ke dalam kolom input di atas.</li>
                        <li>Klik tombol <strong>"Cari"</strong> dan tunggu beberapa saat hingga pratinjau video muncul.</li>
                        <li>Pilih format yang Anda inginkan:
                            <ul>
                                <li><strong>Video Tanpa WM:</strong> Untuk video bersih tanpa logo TikTok.</li>
                                <li><strong>Video dengan WM:</strong> Untuk video asli dengan logo.</li>
                                <li><strong>Audio (MP3):</strong> Untuk mengunduh suara dari video saja.</li>
                                <li><strong>Gambar Slideshow:</strong> Jika video adalah slideshow, setiap gambar akan tersedia untuk diunduh satu per satu.</li>
                            </ul>
                        </li>
                    </ol>
                     <p class="mt-3 small text-muted fst-italic"><strong>Catatan:</strong> Harap gunakan layanan ini secara pribadi dan hormati hak cipta pembuat konten. Jika terjadi kesalahan, periksa kembali tautan Anda atau coba lagi nanti.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('tiktok-form'), submitBtn = document.getElementById('submit-btn'), btnText = document.getElementById('btn-text'), spinner = document.getElementById('spinner'), resultContainer = document.getElementById('result-container');
        form.addEventListener('submit', function (e) { e.preventDefault(); const url = document.getElementById('url').value.trim(); if (!url) return; setLoading(true); resultContainer.innerHTML = ''; fetch(`?url=${encodeURIComponent(url)}`).then(r => r.json().then(data => ({ok: r.ok, data}))).then(({ok, data}) => { if (!ok) throw new Error(data.error || 'Server Error'); if (data.code === 0 && data.data) displayResult(data.data); else displayError(data.msg || 'Request failed.'); }).catch(err => displayError(err.message)).finally(() => setLoading(false)); });
        function setLoading(isLoading) { btnText.classList.toggle('d-none', isLoading); spinner.classList.toggle('d-none', !isLoading); submitBtn.disabled = isLoading; resultContainer.style.opacity = isLoading ? '0.5' : '1'; }
        function displayError(message) { resultContainer.innerHTML = `<div class="alert alert-danger mt-4"><i class="fas fa-exclamation-triangle me-2"></i><strong>Gagal:</strong> ${escapeHtml(message)}</div>`; }
        function displayResult(data) {
            const safeTitle = (data.title || 'tiktok').replace(/[^a-z0-9_-]/gi, '_').substring(0, 50); const isSlideshow = data.images && data.images.length > 0; let buttonsHtml = '', slideshowHtml = '';
            if (data.music) buttonsHtml += createDownloadButton('btn-warning', data.music, `${safeTitle}.mp3`, '<i class="fas fa-music me-2"></i>Audio (.mp3)'); if (!isSlideshow && data.play) buttonsHtml += createDownloadButton('btn-success', data.play, `${safeTitle}_no_wm.mp4`, '<i class="fas fa-video me-2"></i>Tanpa WM (.mp4)');
            if (isSlideshow) { slideshowHtml = '<h3 class="h5 text-center mt-4 mb-3">Hasil Slideshow</h3><div class="row g-3">'; data.images.forEach((img, i) => { const filename = `${safeTitle}_slide_${i + 1}.jpeg`; const downloadUrl = `?download_url=${encodeURIComponent(img)}&filename=${encodeURIComponent(filename)}`; slideshowHtml += `<div class="col-6 col-sm-4 col-md-3"><div class="text-center"><a href="${escapeHtml(img)}" target="_blank"><img src="${escapeHtml(img)}" class="img-fluid rounded-3 shadow-sm" alt="Slide ${i + 1}"></a><a href="${downloadUrl}" class="btn btn-sm btn-outline-success mt-2 rounded-pill">Unduh</a></div></div>`; }); slideshowHtml += '</div>'; }
            resultContainer.innerHTML = `<div class="p-4 rounded-3 mt-4" style="background: #f8f9fa;"><div class="text-center">${data.cover ? `<img src="${escapeHtml(data.cover)}" class="rounded-circle shadow" style="width:100px; height: 100px; object-fit: cover;" alt="Avatar">` : ''}<p class="h5 fw-bold mt-3 mb-3">${escapeHtml(data.title || 'Tidak ada judul')}</p></div><div class="d-flex justify-content-center flex-wrap gap-2">${buttonsHtml || '<p>Link unduhan tidak tersedia.</p>'}</div>${slideshowHtml}</div>`;
        }
        function createDownloadButton(color, url, file, text) { return `<a href="?download_url=${encodeURIComponent(url)}&filename=${encodeURIComponent(file)}" class="btn ${color} rounded-pill shadow-sm px-3" style="font-weight: 500;">${text}</a>`; }
        function escapeHtml(unsafe) { return (unsafe || "").replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'})[m]); }
    });
</script>

<?php include 'footer.php'; ?>
