-- ============================================================
-- Migration: SO v2 — Add status_so, stok_adjustment, klarifikasi_stok
-- Tables   : tc_stok_opname  /  tc_stok_opname_nm
-- Run once against MSSQL before deploying the updated module.
-- ============================================================

-- ── tc_stok_opname (medis) ───────────────────────────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'status_so'
)
    ALTER TABLE tc_stok_opname ADD status_so NVARCHAR(10) NULL;
    -- values: NULL (belum input) | 'draft' | 'final'

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'stok_adjustment'
)
    ALTER TABLE tc_stok_opname ADD stok_adjustment INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'klarifikasi_stok'
)
    ALTER TABLE tc_stok_opname ADD klarifikasi_stok NVARCHAR(500) NULL;

-- ── tc_stok_opname_nm (non-medis / gudang) ──────────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'status_so'
)
    ALTER TABLE tc_stok_opname_nm ADD status_so NVARCHAR(10) NULL;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'stok_adjustment'
)
    ALTER TABLE tc_stok_opname_nm ADD stok_adjustment INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'klarifikasi_stok'
)
    ALTER TABLE tc_stok_opname_nm ADD klarifikasi_stok NVARCHAR(500) NULL;

-- ── tc_stok_opname — snapshot columns (v2.1) ─────────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'stok_akhir_berjalan'
)
    ALTER TABLE tc_stok_opname ADD stok_akhir_berjalan INT NULL;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'total_pemasukan'
)
    ALTER TABLE tc_stok_opname ADD total_pemasukan INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'total_pengeluaran'
)
    ALTER TABLE tc_stok_opname ADD total_pengeluaran INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'stok_final'
)
    ALTER TABLE tc_stok_opname ADD stok_final INT NULL;

-- ── tc_stok_opname_nm — snapshot columns (v2.1) ───────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'stok_akhir_berjalan'
)
    ALTER TABLE tc_stok_opname_nm ADD stok_akhir_berjalan INT NULL;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'total_pemasukan'
)
    ALTER TABLE tc_stok_opname_nm ADD total_pemasukan INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'total_pengeluaran'
)
    ALTER TABLE tc_stok_opname_nm ADD total_pengeluaran INT NULL DEFAULT 0;

IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'stok_final'
)
    ALTER TABLE tc_stok_opname_nm ADD stok_final INT NULL;

-- ── tc_stok_opname — final_date column (v2.2) ───────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'final_date'
)
    ALTER TABLE tc_stok_opname ADD final_date DATETIME NULL;

-- ── tc_stok_opname_nm — final_date column (v2.2) ─────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'final_date'
)
    ALTER TABLE tc_stok_opname_nm ADD final_date DATETIME NULL;

-- ── tc_stok_opname — selisih column (v2.2) ───────────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname' AND COLUMN_NAME = 'selisih'
)
    ALTER TABLE tc_stok_opname ADD selisih INT NULL;
    -- formula: stok_sekarang + stok_exp + stok_adjustment - stok_akhir_berjalan

-- ── tc_stok_opname_nm — selisih column (v2.2) ────────────────
IF NOT EXISTS (
    SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'tc_stok_opname_nm' AND COLUMN_NAME = 'selisih'
)
    ALTER TABLE tc_stok_opname_nm ADD selisih INT NULL;

-- ── Verification ─────────────────────────────────────────────
SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME IN ('tc_stok_opname','tc_stok_opname_nm')
  AND COLUMN_NAME IN ('status_so','stok_adjustment','klarifikasi_stok',
                      'stok_akhir_berjalan','total_pemasukan','total_pengeluaran',
                      'stok_final','selisih')
ORDER BY TABLE_NAME, COLUMN_NAME;
