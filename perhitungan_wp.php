<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// 1. Fetch Data
$kriteria = $pdo->query("SELECT * FROM kriteria ORDER BY kode ASC")->fetchAll();
$alternatif = $pdo->query("SELECT * FROM alternatif ORDER BY id ASC")->fetchAll();
$scores = $pdo->query("SELECT * FROM penilaian")->fetchAll();

$matrix_x = [];
foreach ($scores as $s) {
    $matrix_x[$s['id_alternatif']][$s['id_kriteria']] = $s['nilai'];
}

// 2. WP Calculation - Step 1: Weight Normalization
$total_w = 0;
foreach ($kriteria as $k) $total_w += $k['bobot'];

$normalized_w = [];
foreach ($kriteria as $k) {
    $normalized_w[$k['id']] = $k['bobot'] / $total_w;
}

// 3. WP Calculation - Step 2: Vector S
$vector_s = [];
$total_s = 0;
foreach ($alternatif as $a) {
    $alt_id = $a['id'];
    $s_val = 1;
    foreach ($kriteria as $k) {
        $crit_id = $k['id'];
        $val = $matrix_x[$alt_id][$crit_id] ?? 1; // Default 1 to avoid math error
        $w = $normalized_w[$crit_id];

        if ($k['type'] == 'cost') $w = -$w;
        
        $s_val *= pow($val, $w);
    }
    $vector_s[$alt_id] = $s_val;
    $total_s += $s_val;
}

// 4. WP Calculation - Step 3: Vector V
$ranks_wp = [];
foreach ($alternatif as $a) {
    $alt_id = $a['id'];
    $ranks_wp[$alt_id] = $vector_s[$alt_id] / $total_s;
}

// Sort for Ranking
arsort($ranks_wp);
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4 text-center">Proses Perhitungan Weighted Product (WP)</h2>

        <!-- Step 1: Normalized Weights -->
        <div class="glass-card">
            <h4>1. Normalisasi Bobot (W)</h4>
            <p class="text-muted small">Bobot awal dibagi dengan total semua bobot sehingga totalnya menjadi 1.</p>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot Awal</th>
                            <th>Bobot Ternormalisasi (wj)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $k): ?>
                        <tr>
                            <td><?php echo $k['nama']; ?> (<?php echo $k['kode']; ?>)</td>
                            <td><?php echo $k['bobot']; ?></td>
                            <td class="fw-bold"><?php echo number_format($normalized_w[$k['id']], 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-pink">
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>1.000</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 2: Vector S -->
        <div class="glass-card">
            <h4>2. Perhitungan Nilai Vektor S</h4>
            <p class="text-muted small">Dihitung dengan memangkatkan nilai alternatif dengan bobot ternormalisasi.</p>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Alternatif</th>
                            <th>Perhitungan (S)</th>
                            <th>Hasil Vektor S</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatif as $a): ?>
                        <tr>
                            <td class="text-start"><strong><?php echo $a['nama']; ?></strong></td>
                            <td class="small">
                                <?php 
                                $expr = [];
                                foreach($kriteria as $k) {
                                    $w = number_format($normalized_w[$k['id']], 2);
                                    if($k['type'] == 'cost') $w = "-".$w;
                                    $expr[] = "(" . ($matrix_x[$a['id']][$k['id']] ?? 0) . "<sup>$w</sup>)";
                                }
                                echo implode(" x ", $expr);
                                ?>
                            </td>
                            <td class="fw-bold"><?php echo number_format($vector_s[$a['id']], 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-pink">
                            <td colspan="2"><strong>Total Vektor S</strong></td>
                            <td><strong><?php echo number_format($total_s, 4); ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 3: Vector V & Ranking -->
        <div class="glass-card">
            <h4>3. Hasil Akhir Vektor V (WP)</h4>
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="bg-pink text-white">
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai Vektor V</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($ranks_wp as $alt_id => $value): 
                            $name = "";
                            foreach($alternatif as $a) if($a['id'] == $alt_id) $name = $a['nama'];
                        ?>
                        <tr>
                            <td><span class="badge rounded-pill bg-pink px-3"><?php echo $no++; ?></span></td>
                            <td><strong><?php echo $name; ?></strong></td>
                            <td class="text-primary-pink fw-bold"><?php echo number_format($value, 4); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
