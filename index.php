<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Get counts
$stmtK = $pdo->query("SELECT COUNT(*) FROM kriteria");
$countK = $stmtK->fetchColumn();

$stmtA = $pdo->query("SELECT COUNT(*) FROM alternatif");
$countA = $stmtA->fetchColumn();
?>

<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h1 class="display-4">Selamat Datang di SPK Kost</h1>
        <p class="lead">Sistem Pendukung Keputusan Pemilihan Kost Terbaik menggunakan metode SAW & WP</p>
    </div>
</div>

<div class="row text-center">
    <div class="col-md-6 mb-4">
        <div class="glass-card">
            <i class="fas fa-list-check fa-3x mb-3 text-primary-pink"></i>
            <h3><?php echo $countK; ?> Kriteria</h3>
            <p>Parameter yang digunakan dalam penilaian kost.</p>
            <a href="kriteria.php" class="btn btn-pink mt-2">Kelola Kriteria</a>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="glass-card">
            <i class="fas fa-users fa-3x mb-3 text-secondary-pink"></i>
            <h3><?php echo $countA; ?> Alternatif</h3>
            <p>Daftar kos-kosan yang akan dihitung dan dibandingkan.</p>
            <a href="alternatif.php" class="btn btn-pink mt-2">Kelola Alternatif</a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="glass-card">
            <h2 class="mb-4"><i class="fas fa-info-circle me-2"></i>Tentang Sistem</h2>
            <p>Sistem ini dirancang untuk membantu mahasiswa dalam menentukan pilihan kost terbaik di sekitar kampus.
                Dengan menggunakan data real, sistem ini memproses kriteria seperti harga, jarak, dan fasilitas
                menggunakan dua metode ilmiah:</p>
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded shadow-sm bg-white mb-3">
                        <h5 class="text-pink"><i class="fas fa-calculator me-2"></i>Simple Additive Weighting (SAW)</h5>
                        <p class="small text-muted">Sering dikenal sebagai metode penjumlahan terbobot. Mencari
                            penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded shadow-sm bg-white mb-3">
                        <h5 class="text-pink"><i class="fas fa-chart-line me-2"></i>Weighted Product (WP)</h5>
                        <p class="small text-muted">Menggunakan perkalian untuk menghubungkan rating atribut, di mana
                            rating setiap atribut harus dipangkatkan terlebih dahulu dengan bobot atribut yang
                            bersangkutan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>