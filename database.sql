CREATE DATABASE IF NOT EXISTS spk_kost;
USE spk_kost;

-- Criteria Table
CREATE TABLE IF NOT EXISTS kriteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    type ENUM('benefit', 'cost') NOT NULL,
    bobot FLOAT NOT NULL
);

-- Alternatives Table
CREATE TABLE IF NOT EXISTS alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT
);

-- Assessment Table
CREATE TABLE IF NOT EXISTS penilaian (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_alternatif INT NOT NULL,
    id_kriteria INT NOT NULL,
    nilai FLOAT NOT NULL,
    FOREIGN KEY (id_alternatif) REFERENCES alternatif(id) ON DELETE CASCADE,
    FOREIGN KEY (id_kriteria) REFERENCES kriteria(id) ON DELETE CASCADE
);

-- Seed Criteria
INSERT INTO kriteria (kode, nama, type, bobot) VALUES
('C1', 'Harga Sewa', 'cost', 0.30),
('C2', 'Jarak ke Kampus', 'cost', 0.25),
('C3', 'Fasilitas Kamar', 'benefit', 0.20),
('C4', 'Keamanan', 'benefit', 0.15),
('C5', 'Kebersihan', 'benefit', 0.10);

-- Seed Alternatives
INSERT INTO alternatif (nama, alamat) VALUES
('Kost Mawar Pink', 'Jl. Pendidikan No. 1'),
('Kost Sakura', 'Jl. Merdeka No. 45'),
('Kost Melati Putih', 'Jl. Kenanga No. 12'),
('Kost Lavender', 'Jl. Anggrek No. 8'),
('Kost Tulip', 'Jl. Dahlia No. 5');

-- Seed Assessments (Example values 1-5)
-- Kost Mawar Pink
INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES
(1, 1, 3), (1, 2, 2), (1, 3, 5), (1, 4, 4), (1, 5, 5),
-- Kost Sakura
(2, 1, 5), (2, 2, 4), (2, 3, 3), (2, 4, 3), (2, 5, 4),
-- Kost Melati Putih
(3, 1, 2), (3, 2, 1), (3, 3, 4), (3, 4, 5), (3, 5, 3),
-- Kost Lavender
(4, 1, 4), (4, 2, 5), (4, 3, 2), (4, 4, 2), (4, 5, 2),
-- Kost Tulip
(5, 1, 3), (5, 2, 3), (5, 3, 4), (5, 4, 4), (5, 5, 4);
