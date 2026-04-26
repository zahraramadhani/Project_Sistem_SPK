<?php
require_once 'config/database.php';

// Re-calculate both for comparison (copied logic for simplicity in one file)
$kriteria = $pdo->query("SELECT * FROM kriteria ORDER BY kode ASC")->fetchAll();
$alternatif = $pdo->query("SELECT * FROM alternatif ORDER BY id ASC")->fetchAll();
$scores = $pdo->query("SELECT * FROM penilaian")->fetchAll();

$matrix_x = [];
foreach ($scores as $s) {
    $matrix_x[$s['id_alternatif']][$s['id_kriteria']] = $s['nilai'];
}

// SAW
$matrix_r = [];
foreach ($kriteria as $k) {
    $crit_id = $k['id'];
    $all_values = array_column($matrix_x, $crit_id);
    if (!empty($all_values)) {
        $max = max($all_values);
        $min = min($all_values);
        foreach ($alternatif as $a) {
            $val = $matrix_x[$a['id']][$crit_id] ?? 0;
            $matrix_r[$a['id']][$crit_id] = ($k['type'] == 'benefit') ? $val / $max : $min / $val;
        }
    }
}
$ranks_saw = [];
foreach ($alternatif as $a) {
    $total_v = 0;
    foreach ($kriteria as $k) $total_v += (($matrix_r[$a['id']][$k['id']] ?? 0) * $k['bobot']);
    $ranks_saw[$a['id']] = $total_v;
}
arsort($ranks_saw);

// WP
$total_w = 0;
foreach ($kriteria as $k) $total_w += $k['bobot'];
$normalized_w = [];
foreach ($kriteria as $k) $normalized_w[$k['id']] = $k['bobot'] / $total_w;
$vector_s = [];
$total_s = 0;
foreach ($alternatif as $a) {
    $s_val = 1;
    foreach ($kriteria as $k) {
        $val = $matrix_x[$a['id']][$k['id']] ?? 1;
        $w = $normalized_w[$k['id']];
        if ($k['type'] == 'cost') $w = -$w;
        $s_val *= pow($val, $w);
    }
    $vector_s[$a['id']] = $s_val;
    $total_s += $s_val;
}
$ranks_wp = [];
foreach ($alternatif as $a) $ranks_wp[$a['id']] = $vector_s[$a['id']] / $total_s;
arsort($ranks_wp);

require_once 'includes/header.php';
?>

<div class="row">
    <div class="col-md-12 text-center mb-5">
        <h2><i class="fas fa-ranking-star me-2"></i>Perbandingan Hasil Akhir</h2>
        <p class="text-muted">Membandingkan hasil rekomendasi dari metode SAW dan WP</p>
    </div>
</div>

<div class="row">
    <!-- SAW RESULTS -->
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary-pink text-white p-3 rounded-circle me-3">
                    <i class="fas fa-calculator"></i>
                </div>
                <h4 class="mb-0">Metode SAW</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Alternatif</th>
                            <th class="text-end">Nilai (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($ranks_saw as $alt_id => $val): 
                            $name = "";
                            foreach($alternatif as $a) if($a['id'] == $alt_id) $name = $a['nama'];
                        ?>
                        <tr class="<?php echo ($no == 1) ? 'table-pink' : ''; ?>">
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $name; ?></strong></td>
                            <td class="text-end fw-bold"><?php echo number_format($val, 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($ranks_saw)): ?>
                <div class="alert alert-info border-0 shadow-sm mt-3">
                    <i class="fas fa-lightbulb me-2"></i> Rekomendasi SAW: 
                    <strong><?php reset($ranks_saw); $top_saw = key($ranks_saw); foreach($alternatif as $a) if($a['id'] == $top_saw) echo $a['nama']; ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- WP RESULTS -->
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-secondary-pink text-white p-3 rounded-circle me-3">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4 class="mb-0">Metode WP</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Alternatif</th>
                            <th class="text-end">Nilai (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($ranks_wp as $alt_id => $val): 
                            $name = "";
                            foreach($alternatif as $a) if($a['id'] == $alt_id) $name = $a['nama'];
                        ?>
                        <tr class="<?php echo ($no == 1) ? 'table-pink' : ''; ?>">
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo $name; ?></strong></td>
                            <td class="text-end fw-bold"><?php echo number_format($val, 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($ranks_wp)): ?>
                <div class="alert alert-info border-0 shadow-sm mt-3">
                    <i class="fas fa-lightbulb me-2"></i> Rekomendasi WP: 
                    <strong><?php reset($ranks_wp); $top_wp = key($ranks_wp); foreach($alternatif as $a) if($a['id'] == $top_wp) echo $a['nama']; ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="glass-card text-center py-5">
            <h3 class="mb-4">Kesimpulan</h3>
            <p class="lead">
                <?php 
                if (!empty($ranks_saw) && !empty($ranks_wp)) {
                    $top_saw_id = key($ranks_saw);
                    $top_wp_id = key($ranks_wp);
                    
                    if ($top_saw_id == $top_wp_id) {
                        echo "Kedua metode memberikan hasil yang <strong>konsisten</strong>. Pilihan terbaik untuk Kost adalah <strong>" . $name = ""; foreach($alternatif as $a) if($a['id'] == $top_saw_id) echo $a['nama'] . "</strong>.";
                    } else {
                        echo "Terdapat perbedaan rekomendasi antara metode SAW dan WP. Anda dapat mempertimbangkan kedua hasil tersebut berdasarkan prioritas kriteria.";
                    }
                }
                ?>
            </p>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
