-- Tambah kolom verifikasi pada tc_mod_laporan
-- Jalankan query ini di SQL Server

ALTER TABLE tc_mod_laporan ADD verified_by NVARCHAR(50) NULL;
ALTER TABLE tc_mod_laporan ADD verified_at DATETIME NULL;
