<?php
require_once 'config/database.php';
require_once 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM kriteria WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: kriteria.php?msg=deleted");
}

// Handle Form Submit
if (isset($_POST['submit'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $type = $_POST['type'];
    $bobot = $_POST['bobot'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE kriteria SET kode=?, nama=?, type=?, bobot=? WHERE id=?");
        $stmt->execute([$kode, $nama, $type, $bobot, $_POST['id']]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO kriteria (kode, nama, type, bobot) VALUES (?, ?, ?, ?)");
        $stmt->execute([$kode, $nama, $type, $bobot]);
    }
    header("Location: kriteria.php?msg=success");
}

// Get data for Edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM kriteria WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_data = $stmt->fetch();
}

// Get All Criteria
$stmt = $pdo->query("SELECT * FROM kriteria ORDER BY kode ASC");
$kriteria = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <div class="glass-card">
            <h3><i class="fas fa-edit me-2"></i><?php echo $edit_data ? 'Edit' : 'Tambah'; ?> Kriteria</h3>
            <hr>
            <form action="" method="POST">
                <?php if ($edit_data): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label class="form-label">Kode Kriteria</label>
                    <input type="text" name="kode" class="form-control" placeholder="Contoh: C1" value="<?php echo $edit_data['kode'] ?? ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Kriteria</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Harga" value="<?php echo $edit_data['nama'] ?? ''; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <select name="type" class="form-select" required>
                        <option value="benefit" <?php echo (isset($edit_data['type']) && $edit_data['type'] == 'benefit') ? 'selected' : ''; ?>>Benefit (Semakin besar semakin baik)</option>
                        <option value="cost" <?php echo (isset($edit_data['type']) && $edit_data['type'] == 'cost') ? 'selected' : ''; ?>>Cost (Semakin kecil semakin baik)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bobot (0-1)</label>
                    <input type="number" step="0.01" name="bobot" class="form-control" placeholder="Contoh: 0.25" value="<?php echo $edit_data['bobot'] ?? ''; ?>" required>
                </div>
                <div class="d-grid">
                    <button type="submit" name="submit" class="btn btn-pink">
                        <i class="fas fa-save me-1"></i> Simpan Kriteria
                    </button>
                    <?php if ($edit_data): ?>
                        <a href="kriteria.php" class="btn btn-outline-secondary mt-2">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3><i class="fas fa-table me-2"></i>Daftar Kriteria</h3>
                <span class="badge-pink">Total: <?php echo count($kriteria); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Tipe</th>
                            <th>Bobot</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $k): ?>
                        <tr>
                            <td><strong><?php echo $k['kode']; ?></strong></td>
                            <td><?php echo $k['nama']; ?></td>
                            <td>
                                <span class="badge <?php echo $k['type'] == 'benefit' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                    <?php echo ucfirst($k['type']); ?>
                                </span>
                            </td>
                            <td><?php echo $k['bobot']; ?></td>
                            <td>
                                <a href="kriteria.php?edit=<?php echo $k['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <a href="kriteria.php?delete=<?php echo $k['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kriteria ini?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($kriteria)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada data kriteria.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
