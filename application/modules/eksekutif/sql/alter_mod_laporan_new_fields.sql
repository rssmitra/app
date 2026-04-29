-- ============================================================
-- ALTER TABLE: Tambah field baru untuk Laporan MOD
-- 1. Farmasi  : jml_obat_ditinggal
-- 2. Radiologi: jml_rontgen_belum_expertise
-- ============================================================

-- Farmasi: Jumlah obat ditinggal
ALTER TABLE tc_mod_farmasi ADD jml_obat_ditinggal INT DEFAULT 0;

-- Radiologi: Jumlah hasil rontgen yang belum ada expertise
ALTER TABLE tc_mod_radiologi ADD jml_rontgen_belum_expertise INT DEFAULT 0;

-- ============================================================
-- CREATE TABLE: tc_mod_farmasi_cito
-- Obat/Alkes Cito atau Pembelian ke luar cito
-- ============================================================
CREATE TABLE tc_mod_farmasi_cito (
    id INT IDENTITY(1,1) PRIMARY KEY,
    laporan_id INT NOT NULL,
    nama_obat NVARCHAR(255) NOT NULL,
    jumlah INT DEFAULT 0,
    harga DECIMAL(18,2) DEFAULT 0
);

-- ============================================================
-- CREATE TABLE: tc_mod_obat_kosong
-- Ranap/OK: Obat/Alkes yang kosong
-- ============================================================
CREATE TABLE tc_mod_obat_kosong (
    id INT IDENTITY(1,1) PRIMARY KEY,
    laporan_id INT NOT NULL,
    nama_obat_alkes NVARCHAR(255) NOT NULL
);
