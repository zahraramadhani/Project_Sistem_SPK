<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Get Criteria for assessment
$stmtK = $pdo->query("SELECT * FROM kriteria ORDER BY kode ASC");
$kriteria = $stmtK->fetchAll();

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM alternatif WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: alternatif.php?msg=deleted");
}

// Handle Form Submit
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $id_alternatif = $_POST['id'] ?? null;

    if ($id_alternatif) {
        // Update Alternatif
        $stmt = $pdo->prepare("UPDATE alternatif SET nama=?, alamat=? WHERE id=?");
        $stmt->execute([$nama, $alamat, $id_alternatif]);
    } else {
        // Insert Alternatif
        $stmt = $pdo->prepare("INSERT INTO alternatif (nama, alamat) VALUES (?, ?)");
        $stmt->execute([$nama, $alamat]);
        $id_alternatif = $pdo->lastInsertId();
    }

    // Handle Scores
    foreach ($kriteria as $k) {
        $nilai = $_POST['kriteria_' . $k['id']];
        // Check if exists
        $stmtCheck = $pdo->prepare("SELECT id FROM penilaian WHERE id_alternatif = ? AND id_kriteria = ?");
        $stmtCheck->execute([$id_alternatif, $k['id']]);
        if ($stmtCheck->fetch()) {
            $stmtUpd = $pdo->prepare("UPDATE penilaian SET nilai = ? WHERE id_alternatif = ? AND id_kriteria = ?");
            $stmtUpd->execute([$nilai, $id_alternatif, $k['id']]);
        } else {
            $stmtIns = $pdo->prepare("INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES (?, ?, ?)");
            $stmtIns->execute([$id_alternatif, $k['id'], $nilai]);
        }
    }
    header("Location: alternatif.php?msg=success");
}

// Get data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM alternatif WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
    
    // Get scores for edit
    $stmtS = $pdo->prepare("SELECT id_kriteria, nilai FROM penilaian WHERE id_alternatif = ?");
    $stmtS->execute([$_GET['edit']]);
    $scores = $stmtS->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Get All Alternatives with their scores
$stmtA = $pdo->query("SELECT * FROM alternatif ORDER BY id DESC");
$alternatif = $stmtA->fetchAll();

// Get all scores for display
$all_scores = [];
$stmtAS = $pdo->query("SELECT * FROM penilaian");
while ($row = $stmtAS->fetch()) {
    $all_scores[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="glass-card">
            <h3><i class="fas fa-edit me-2"></i><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Alternatif & Penilaian</h3>
            <hr>
            <form action="" method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Kost</label>
                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Kost Mawar Pink" value="<?php echo $edit_data['nama'] ?? ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat / Keterangan</label>
                            <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Raya No..."><?php echo $edit_data['alamat'] ?? ''; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border">
                            <h5 class="mb-3"><i class="fas fa-star text-warning me-2"></i>Penilaian Kriteria (1-5)</h5>
                            <?php foreach ($kriteria as $k): ?>
                                <div class="mb-2 row align-items-center">
                                    <label class="col-sm-6 col-form-label"><?php echo $k['nama']; ?> (<?php echo $k['kode']; ?>)</label>
                                    <div class="col-sm-6">
                                        <input type="number" step="0.1" name="kriteria_<?php echo $k['id']; ?>" class="form-control" value="<?php echo $scores[$k['id']] ?? '0'; ?>" required>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-end">
                    <?php if ($edit_data): ?>
                        <a href="alternatif.php" class="btn btn-outline-secondary me-2">Batal</a>
                    <?php endif; ?>
                    <button type="submit" name="submit" class="btn btn-pink px-5">
                        <i class="fas fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-users me-2"></i>Daftar Alternatif & Nilai</h3>
                <span class="badge-pink">Total: <?php echo count($alternatif); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Kost</th>
                            <?php foreach ($kriteria as $k): ?>
                                <th class="text-center"><?php echo $k['kode']; ?></th>
                            <?php endforeach; ?>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alternatif as $a): ?>
                        <tr>
                            <td>
                                <strong><?php echo $a['nama']; ?></strong><br>
                                <small class="text-muted"><?php echo $a['alamat']; ?></small>
                            </td>
                            <?php foreach ($kriteria as $k): ?>
                                <td class="text-center"><?php echo $all_scores[$a['id']][$k['id']] ?? '-'; ?></td>
                            <?php endforeach; ?>
                            <td class="text-center">
                                <a href="alternatif.php?edit=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <a href="alternatif.php?delete=<?php echo $a['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus alternatif ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($alternatif)): ?>
                        <tr>
                            <td colspan="<?php echo count($kriteria) + 2; ?>" class="text-center py-4">Belum ada data alternatif.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
