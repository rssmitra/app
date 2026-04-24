-- ============================================================
-- Tabel detail pasien VK (Ruang Bersalin)
-- Relasi: tc_mod_laporan (1) → tc_mod_vk_detail (N)
-- ============================================================
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tc_mod_vk_detail' AND xtype='U')
BEGIN
    CREATE TABLE tc_mod_vk_detail (
        id          INT IDENTITY(1,1) PRIMARY KEY,
        laporan_id  INT NOT NULL,
        nama_umur   NVARCHAR(200) NOT NULL DEFAULT '',
        jaminan     NVARCHAR(100) NOT NULL DEFAULT '',
        diagnosa    NVARCHAR(500) NOT NULL DEFAULT '',
        dpjp        NVARCHAR(200) NOT NULL DEFAULT '',
        CONSTRAINT FK_vk_detail_laporan FOREIGN KEY (laporan_id)
            REFERENCES tc_mod_laporan(id) ON DELETE CASCADE
    );

    CREATE INDEX IX_vk_detail_laporan ON tc_mod_vk_detail(laporan_id);
END
GO

-- ============================================================
-- Tabel detail pasien Perina (Bayi Sakit)
-- Relasi: tc_mod_laporan (1) → tc_mod_perina_detail (N)
-- ============================================================
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='tc_mod_perina_detail' AND xtype='U')
BEGIN
    CREATE TABLE tc_mod_perina_detail (
        id          INT IDENTITY(1,1) PRIMARY KEY,
        laporan_id  INT NOT NULL,
        nama_umur   NVARCHAR(200) NOT NULL DEFAULT '',
        jaminan     NVARCHAR(100) NOT NULL DEFAULT '',
        diagnosa    NVARCHAR(500) NOT NULL DEFAULT '',
        dpjp        NVARCHAR(200) NOT NULL DEFAULT '',
        CONSTRAINT FK_perina_detail_laporan FOREIGN KEY (laporan_id)
            REFERENCES tc_mod_laporan(id) ON DELETE CASCADE
    );

    CREATE INDEX IX_perina_detail_laporan ON tc_mod_perina_detail(laporan_id);
END
GO
