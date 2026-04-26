USE spk_kost;

-- Clear old data
DELETE FROM penilaian;
DELETE FROM alternatif;

-- Reset Auto Increment
ALTER TABLE alternatif AUTO_INCREMENT = 1;

-- Insert 10 Real Alternatives from Mamikos with REAL DATA (Rupiah & Meters)
INSERT INTO alternatif (nama, alamat) VALUES
('Kost Ibu Aris Non AC', 'Pamulang, Tangerang Selatan (Dekat UNPAM Viktor)'),
('Kost Hive Living Tipe D', 'Serpong, Tangerang Selatan'),
('Kost Hive Living Tipe E', 'Serpong, Tangerang Selatan'),
('Kost The Best Kost Tipe B', 'Serpong, Tangerang Selatan'),
('Kost The Best Kost Tipe A', 'Serpong, Tangerang Selatan'),
('Kost The Best Kost Tipe C', 'Serpong, Tangerang Selatan'),
('Kost Hive Living Tipe A', 'Serpong, Tangerang Selatan'),
('Kost Saga Bukit Dago', 'Kec. Setu, Tangerang Selatan'),
('Kost Hive Living Tipe C', 'Serpong, Tangerang Selatan'),
('Kost Exclusive Buana Residence', 'Serpong, Tangerang Selatan');

-- Insert Assessments with REAL DATA
-- C1: Harga (Dalam Rupiah) - TYPE: COST
-- C2: Jarak (Dalam Meter dari UNPAM Viktor) - TYPE: COST
-- C3-C5: Tetap skala 1-5 (Kualitatif) - TYPE: BENEFIT

-- 1. Kost Ibu Aris Non AC (900k, 300m)
INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES
(1, 1, 900000), (1, 2, 300), (1, 3, 2), (1, 4, 3), (1, 5, 3),
-- 2. Kost Hive Living Tipe D (1.68m, 2500m)
(2, 1, 1680000), (2, 2, 2500), (2, 3, 5), (2, 4, 4), (2, 5, 4),
-- 3. Kost Hive Living Tipe E (1.49m, 2500m)
(3, 1, 1490000), (3, 2, 2500), (3, 3, 5), (3, 4, 4), (3, 5, 4),
-- 4. Kost The Best Kost Tipe B (1.7m, 3000m)
(4, 1, 1700000), (4, 2, 3000), (4, 3, 5), (4, 4, 4), (4, 5, 4),
-- 5. Kost The Best Kost Tipe A (1.74m, 3000m)
(5, 1, 1740000), (5, 2, 3000), (5, 3, 5), (5, 4, 4), (5, 5, 4),
-- 6. Kost The Best Kost Tipe C (1.6m, 3000m)
(6, 1, 1600000), (6, 2, 3000), (6, 3, 5), (6, 4, 4), (6, 5, 4),
-- 7. Kost Hive Living Tipe A (2.04m, 2500m)
(7, 1, 2040000), (7, 2, 2500), (7, 3, 5), (7, 4, 5), (7, 5, 5),
-- 8. Kost Saga Bukit Dago (900k, 1200m)
(8, 1, 900000), (8, 2, 1200), (8, 3, 4), (8, 4, 3), (8, 5, 3),
-- 9. Kost Hive Living Tipe C (1.92m, 2500m)
(9, 1, 1920000), (9, 2, 2500), (9, 3, 5), (9, 4, 4), (9, 5, 4),
-- 10. Kost Exclusive Buana Residence (2.0m, 3500m)
(10, 1, 2000000), (10, 2, 3500), (10, 3, 5), (10, 4, 5), (10, 5, 4);
