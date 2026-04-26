<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// 1. Fetch Data
$kriteria = $pdo->query("SELECT * FROM kriteria ORDER BY kode ASC")->fetchAll();
$alternatif = $pdo->query("SELECT * FROM alternatif ORDER BY id ASC")->fetchAll();
$scores = $pdo->query("SELECT * FROM penilaian")->fetchAll();

// Map scores to [alt_id][crit_id]
$matrix_x = [];
foreach ($scores as $s) {
    $matrix_x[$s['id_alternatif']][$s['id_kriteria']] = $s['nilai'];
}

// 2. SAW Calculation - Step 1: Normalization
$matrix_r = [];
foreach ($kriteria as $k) {
    $crit_id = $k['id'];
    $all_values = array_column($matrix_x, $crit_id);
    
    if (empty($all_values)) continue;

    $max = max($all_values);
    $min = min($all_values);

    foreach ($alternatif as $a) {
        $alt_id = $a['id'];
        $val = $matrix_x[$alt_id][$crit_id] ?? 0;

        if ($k['type'] == 'benefit') {
            $matrix_r[$alt_id][$crit_id] = $val / $max;
        } else {
            $matrix_r[$alt_id][$crit_id] = $min / $val;
        }
    }
}

// 3. SAW Calculation - Step 2: Preference (V)
$ranks_saw = [];
foreach ($alternatif as $a) {
    $alt_id = $a['id'];
    $total_v = 0;
    foreach ($kriteria as $k) {
        $crit_id = $k['id'];
        $r_val = $matrix_r[$alt_id][$crit_id] ?? 0;
        $total_v += ($r_val * $k['bobot']);
    }
    $ranks_saw[$alt_id] = $total_v;
}

// Sort for Ranking
arsort($ranks_saw);
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="mb-4 text-center">Proses Perhitungan SAW</h2>
        
        <!-- Step 1: Matrix X -->
        <div class="glass-card">
            <h4>1. Matriks Keputusan (X)</h4>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Alternatif</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?php echo $k['kode']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatif as $a): ?>
                        <tr>
                            <td class="text-start"><strong><?php echo $a['nama']; ?></strong></td>
                            <?php foreach ($kriteria as $k): ?>
                                <td><?php echo $matrix_x[$a['id']][$k['id']] ?? 0; ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 2: Normalization (R) -->
        <div class="glass-card">
            <h4>2. Matriks Ternormalisasi (R)</h4>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Alternatif</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th><?php echo $k['kode']; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatif as $a): ?>
                        <tr>
                            <td class="text-start"><strong><?php echo $a['nama']; ?></strong></td>
                            <?php foreach ($kriteria as $k): ?>
                                <td><?php echo number_format($matrix_r[$a['id']][$k['id']] ?? 0, 3); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Step 3: Ranking -->
        <div class="glass-card">
            <h4>3. Hasil Akhir & Perankingan (SAW)</h4>
            <div class="table-responsive">
                <table class="table table-hover text-center">
                    <thead class="bg-pink text-white">
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Alternatif</th>
                            <th>Nilai Preferensi (V)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach ($ranks_saw as $alt_id => $value): 
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
