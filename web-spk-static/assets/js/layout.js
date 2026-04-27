/**
 * layout.js
 * Handles common UI elements like Navbar and Footer
 */

const Layout = {
    render: () => {
        const header = `
            <nav class="navbar navbar-expand-lg sticky-top">
                <div class="container">
                    <a class="navbar-brand" href="index.html">
                        <i class="fas fa-home-heart me-2"></i>SPK KOST PINK
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="index.html"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="kriteria.html"><i class="fas fa-list-check me-1"></i> Kriteria</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="alternatif.html"><i class="fas fa-users me-1"></i> Alternatif</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-calculator me-1"></i> Perhitungan
                                </a>
                                <ul class="dropdown-menu border-0 shadow">
                                    <li><a class="dropdown-item" href="perhitungan_saw.html">Metode SAW</a></li>
                                    <li><a class="dropdown-item" href="perhitungan_wp.html">Metode WP</a></li>
                                    <li><a class="dropdown-item" href="perhitungan_smart.html">Metode SMART</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="hasil.html"><i class="fas fa-ranking-star me-1"></i> Hasil Akhir</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        `;

        const footer = `
            <div class="watermark">
                <i class="fas fa-user-graduate me-1"></i> Zahra Ramadhani Sanjaya | 231011402596
            </div>

            <footer class="footer">
                <div class="container">
                    <p>&copy; ${new Date().getFullYear()} SPK Pemilihan Kost. Created by <strong>Zahra Ramadhani Sanjaya (231011402596)</strong></p>
                </div>
            </footer>
        `;

        // Insert Navbar at the beginning of body
        document.body.insertAdjacentHTML('afterbegin', header);
        
        // Wrap existing content in container if not already
        const mainContent = document.getElementById('main-content');
        if (mainContent) {
            mainContent.classList.add('container', 'mt-5');
        }

        // Insert Footer at the end of body
        document.body.insertAdjacentHTML('beforeend', footer);

        // Highlight active link
        const currentPath = window.location.pathname.split('/').pop() || 'index.html';
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }
};

// Auto-render on load
document.addEventListener('DOMContentLoaded', Layout.render);
