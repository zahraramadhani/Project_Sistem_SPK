<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Pemilihan Kost - Pink Edition</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-home-heart me-2"></i>SPK KOST PINK
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kriteria.php"><i class="fas fa-list-check me-1"></i> Kriteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="alternatif.php"><i class="fas fa-users me-1"></i> Alternatif</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-calculator me-1"></i> Perhitungan
                        </a>
                        <ul class="dropdown-menu border-0 shadow">
                            <li><a class="dropdown-item" href="perhitungan_saw.php">Metode SAW</a></li>
                            <li><a class="dropdown-item" href="perhitungan_wp.php">Metode WP</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hasil.php"><i class="fas fa-ranking-star me-1"></i> Hasil Akhir</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
