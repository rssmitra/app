<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persediaan_barang_rekap_per_unit_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_flag()
    {
        $flag = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        return ($flag === 'non_medis') ? 'non_medis' : 'medis';
    }

    private function _get_tables($flag = null)
    {
        if ($flag === null) $flag = $this->_get_flag();
        return array(
            'stok_v'  => ($flag === 'non_medis') ? 'mt_depo_stok_nm_v' : 'mt_depo_stok_v',
            'barang'  => ($flag === 'non_medis') ? 'mt_barang_nm'       : 'mt_barang',
            'po_det'  => ($flag === 'non_medis') ? 'tc_po_nm_det'       : 'tc_po_det',
            'po_hdr'  => ($flag === 'non_medis') ? 'tc_po_nm'           : 'tc_po',
            'kartu'   => ($flag === 'non_medis') ? 'tc_kartu_stok_nm'   : 'tc_kartu_stok',
        );
    }

    private function _get_filters()
    {
        $search     = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
        $tgl_filter = !empty($_POST['tgl_filter'])
            ? trim($_POST['tgl_filter'])
            : (!empty($_GET['tgl_filter']) ? trim($_GET['tgl_filter']) : '');

        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) {
            $tgl_filter = '';
        }

        return array(
            'search'     => $search,
            'tgl_filter' => $tgl_filter,
        );
    }

    /**
     * Batas tanggal untuk query historis (PHP literal agar SQL Server pakai index range scan).
     * upper = hari setelah tgl_filter (eksklusif), lower = N tahun sebelum.
     */
    private function _date_range($tgl_filter, $years_back = 1)
    {
        return array(
            'upper' => date('Y-m-d', strtotime($tgl_filter . ' +1 day')),
            'lower' => date('Y-m-d', strtotime($tgl_filter . " -{$years_back} years")),
        );
    }

    /** Weighted-average harga modal formula string (per satuan besar). */
    private function _wa_calc()
    {
        return "CAST(
                    SUM(CAST(jumlah_besar AS FLOAT) *
                        CASE WHEN CAST(discount AS FLOAT) > 0
                            THEN CAST(harga_satuan AS FLOAT)
                                 * (1 + CAST(ppn AS FLOAT) / 100)
                                 * (1 - CAST(discount AS FLOAT) / 100)
                            ELSE CAST(harga_satuan AS FLOAT)
                        END
                    ) / NULLIF(SUM(CAST(jumlah_besar AS FLOAT)), 0)
                AS FLOAT) AS wa_harga_modal";
    }

    // ── DataTable: list rekap per bagian (paginated) ──
    // Two-phase CTE:
    //   Phase 1 — paginate qualifying kode_bagian by stok only (no WA, fast)
    //   Phase 2 — compute WA only for items in the current page's bagians
    public function get_datatables()
    {
        $flag    = $this->_get_flag();
        $t       = $this->_get_tables($flag);
        $f       = $this->_get_filters();
        $length  = isset($_POST['length']) && (int)$_POST['length'] > 0 ? (int)$_POST['length'] : 25;
        $start   = isset($_POST['start'])  ? (int)$_POST['start']  : 0;
        $from    = $start + 1;
        $to      = $start + $length;
        $wa_calc = $this->_wa_calc();

        if (!empty($f['tgl_filter'])) {
            $dr     = $this->_date_range($f['tgl_filter']);
            $params = array();
            $sc     = '';
            if (!empty($f['search'])) {
                $sc       = "AND (mb.nama_bagian LIKE ? OR ks2.kode_bagian LIKE ?)";
                $params[] = '%' . $f['search'] . '%';
                $params[] = '%' . $f['search'] . '%';
            }

            // Phase 1: get paginated kode_bagian from kartu stok (no WA join)
            // Phase 2: get stok + WA only for current page's bagians
            $sql = "
                WITH stok_aktual AS (
                    SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                    FROM (
                        SELECT kode_brg, kode_bagian, stok_akhir,
                               ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                        FROM {$t['kartu']}
                        WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                    ) ki
                    WHERE rn = 1 AND stok_akhir > 0
                ),
                qualifying_bagian AS (
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY ISNULL(mb.nama_bagian, ks2.kode_bagian)) AS rn,
                        ks2.kode_bagian,
                        ISNULL(mb.nama_bagian, ks2.kode_bagian) AS nama_bagian,
                        COUNT(DISTINCT ks2.kode_brg) AS jumlah_item
                    FROM (SELECT DISTINCT kode_brg, kode_bagian FROM stok_aktual) ks2
                    LEFT JOIN mt_bagian mb ON mb.kode_bagian = ks2.kode_bagian
                    WHERE 1=1 {$sc}
                    GROUP BY ks2.kode_bagian, mb.nama_bagian
                ),
                page_bagian AS (
                    SELECT kode_bagian, nama_bagian, jumlah_item
                    FROM qualifying_bagian
                    WHERE rn BETWEEN {$from} AND {$to}
                ),
                wa AS (
                    SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']} d
                        JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                        INNER JOIN (
                            SELECT DISTINCT kode_brg FROM stok_aktual
                            WHERE kode_bagian IN (SELECT kode_bagian FROM page_bagian)
                        ) pg ON pg.kode_brg = d.kode_brg
                        WHERE p.tgl_po >= '{$dr['lower']}' AND p.tgl_po <= '{$f['tgl_filter']}'
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg
                )
                SELECT pb.kode_bagian, pb.nama_bagian, pb.jumlah_item,
                       ISNULL(SUM(ks.jml_sat_kcl *
                           ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                       ), 0) AS total_nilai
                FROM page_bagian pb
                JOIN stok_aktual ks ON ks.kode_bagian = pb.kode_bagian
                LEFT JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
                LEFT JOIN wa ON wa.kode_brg = ks.kode_brg
                GROUP BY pb.kode_bagian, pb.nama_bagian, pb.jumlah_item
                ORDER BY pb.nama_bagian";
        } else {
            $params = array();
            $sc     = '';
            if (!empty($f['search'])) {
                $sc       = "AND (mb.nama_bagian LIKE ? OR ds.kode_bagian LIKE ?)";
                $params[] = '%' . $f['search'] . '%';
                $params[] = '%' . $f['search'] . '%';
            }

            // Phase 1: paginate qualifying kode_bagian by stok (no WA join)
            // Phase 2: WA only for current page's bagians' items
            $sql = "
                WITH qualifying_bagian AS (
                    SELECT
                        ROW_NUMBER() OVER (ORDER BY ISNULL(mb.nama_bagian, ds.kode_bagian)) AS rn,
                        ds.kode_bagian,
                        ISNULL(mb.nama_bagian, ds.kode_bagian) AS nama_bagian,
                        COUNT(DISTINCT ds.kode_brg) AS jumlah_item
                    FROM {$t['stok_v']} ds
                    LEFT JOIN mt_bagian mb ON mb.kode_bagian = ds.kode_bagian
                    WHERE ds.is_active = 1 AND ds.jml_sat_kcl > 0 {$sc}
                    GROUP BY ds.kode_bagian, mb.nama_bagian
                ),
                page_bagian AS (
                    SELECT kode_bagian, nama_bagian, jumlah_item
                    FROM qualifying_bagian
                    WHERE rn BETWEEN {$from} AND {$to}
                ),
                wa AS (
                    SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']} d
                        INNER JOIN (
                            SELECT DISTINCT kode_brg FROM {$t['stok_v']}
                            WHERE is_active = 1
                              AND kode_bagian IN (SELECT kode_bagian FROM page_bagian)
                        ) pg ON pg.kode_brg = d.kode_brg
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg
                )
                SELECT pb.kode_bagian, pb.nama_bagian, pb.jumlah_item,
                       ISNULL(SUM(CAST(ds.jml_sat_kcl AS FLOAT) *
                           ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                       ), 0) AS total_nilai
                FROM page_bagian pb
                JOIN {$t['stok_v']} ds ON ds.kode_bagian = pb.kode_bagian AND ds.is_active = 1 AND ds.jml_sat_kcl > 0
                LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                LEFT JOIN wa ON wa.kode_brg = ds.kode_brg
                GROUP BY pb.kode_bagian, pb.nama_bagian, pb.jumlah_item
                ORDER BY pb.nama_bagian";
        }
        // echo $sql; die;
        return $this->db->query($sql, $params)->result();
    }

    // ── DataTable: recordsFiltered ──
    // Hitung bagian dengan stok > 0 sesuai filter pencarian — tanpa join WA
    public function count_filtered()
    {
        $flag = $this->_get_flag();
        $t    = $this->_get_tables($flag);
        $f    = $this->_get_filters();

        if (!empty($f['tgl_filter'])) {
            $dr     = $this->_date_range($f['tgl_filter']);
            $params = array();
            $search = '';
            if (!empty($f['search'])) {
                $search   = "AND (mb.nama_bagian LIKE ? OR ks.kode_bagian LIKE ?)";
                $params[] = '%' . $f['search'] . '%';
                $params[] = '%' . $f['search'] . '%';
            }
            $sql = "SELECT COUNT(DISTINCT ks.kode_bagian) AS cnt
                    FROM (
                        SELECT kode_bagian
                        FROM (
                            SELECT kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                        ) ki
                        WHERE rn = 1 AND stok_akhir > 0
                    ) ks
                    LEFT JOIN mt_bagian mb ON mb.kode_bagian = ks.kode_bagian
                    WHERE 1=1 {$search}";
        } else {
            $params = array();
            $search = '';
            if (!empty($f['search'])) {
                $search   = "AND (mb.nama_bagian LIKE ? OR ds.kode_bagian LIKE ?)";
                $params[] = '%' . $f['search'] . '%';
                $params[] = '%' . $f['search'] . '%';
            }
            $sql = "SELECT COUNT(DISTINCT ds.kode_bagian) AS cnt
                    FROM {$t['stok_v']} ds
                    LEFT JOIN mt_bagian mb ON mb.kode_bagian = ds.kode_bagian
                    WHERE ds.is_active = 1 AND ds.jml_sat_kcl > 0
                    {$search}";
        }

        $r = $this->db->query($sql, $params)->row();
        return $r ? (int)$r->cnt : 0;
    }

    // ── DataTable: recordsTotal ──
    // Hitung jumlah bagian yang memiliki stok > 0 — tidak perlu join WA hanya untuk menghitung
    public function count_all()
    {
        $flag = $this->_get_flag();
        $t    = $this->_get_tables($flag);
        $f    = $this->_get_filters();

        if (!empty($f['tgl_filter'])) {
            $dr  = $this->_date_range($f['tgl_filter']);
            $sql = "SELECT COUNT(DISTINCT kode_bagian) AS cnt
                    FROM (
                        SELECT kode_bagian
                        FROM (
                            SELECT kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                        ) ki
                        WHERE rn = 1 AND stok_akhir > 0
                    ) x";
            $r = $this->db->query($sql)->row();
        } else {
            $sql = "SELECT COUNT(DISTINCT kode_bagian) AS cnt
                    FROM {$t['stok_v']}
                    WHERE is_active = 1 AND jml_sat_kcl > 0";
            $r = $this->db->query($sql)->row();
        }
        return $r ? (int)$r->cnt : 0;
    }

    /**
     * Summary cards: total unit, total jenis item, total nilai persediaan.
     */
    public function get_summary($flag, $tgl_filter = '')
    {
        $t       = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $sql = "SELECT
                        COUNT(DISTINCT ds.kode_bagian) AS total_unit,
                        COUNT(DISTINCT ds.kode_brg)    AS total_jenis,
                        SUM(ds.jml_sat_kcl *
                            ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                        ) AS total_nilai
                    FROM (
                        SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                        FROM (
                            SELECT kode_brg, kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                        ) ki
                        WHERE ki.rn = 1 AND ki.stok_akhir > 0
                    ) ds
                    LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                    LEFT JOIN (
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']} d
                            JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                            WHERE p.tgl_po >= '{$dr['lower']}' AND p.tgl_po <= '{$tgl_filter}'
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ds.kode_brg";
        } else {
            $sql = "SELECT
                        COUNT(DISTINCT ds.kode_bagian) AS total_unit,
                        COUNT(DISTINCT ds.kode_brg)    AS total_jenis,
                        SUM(CAST(ds.jml_sat_kcl AS FLOAT) *
                            ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                        ) AS total_nilai
                    FROM {$t['stok_v']} ds
                    LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                    LEFT JOIN (
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']} d
                            INNER JOIN (
                                SELECT DISTINCT kode_brg FROM {$t['stok_v']} WHERE is_active = 1
                            ) active ON active.kode_brg = d.kode_brg
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ds.kode_brg
                    WHERE ds.is_active = 1";
        }

        return $this->db->query($sql)->row();
    }

    /**
     * Daftar item (kode_brg) beserta stok, WA harga, untuk satu kode_bagian.
     * Dipakai di child-row detail.
     */
    public function get_detail_items($kode_bagian, $flag = 'medis', $tgl_filter = '')
    {
        $t       = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $sql = "SELECT
                        ks.kode_brg,
                        ISNULL(b.nama_brg, ks.kode_brg) AS nama_brg,
                        b.satuan_kecil, b.satuan_besar,
                        ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                        ks.jml_sat_kcl,
                        ISNULL(wa.wa_harga_modal, 0) AS wa_harga_modal
                    FROM (
                        SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                        FROM (
                            SELECT kode_brg, kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE kode_bagian = ?
                              AND tgl_input < '{$dr['upper']}'
                        ) ki
                        WHERE ki.rn = 1 
                        -- AND ki.stok_akhir > 0
                    ) ks
                    LEFT JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
                    LEFT JOIN (
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']} d
                            JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                            WHERE d.kode_brg IN (
                                SELECT DISTINCT kode_brg FROM {$t['kartu']}
                                WHERE kode_bagian = ?
                                  AND tgl_input < '{$dr['upper']}'
                            )
                            AND p.tgl_po <= '{$tgl_filter}'
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ks.kode_brg
                    WHERE wa.wa_harga_modal > 0
                    ORDER BY ISNULL(b.nama_brg, ks.kode_brg)";
            // echo $sql; die;
            return $this->db->query($sql, array($kode_bagian, $kode_bagian))->result();
        }

        $sql = "SELECT
                    ds.kode_brg,
                    ISNULL(b.nama_brg, ds.kode_brg) AS nama_brg,
                    b.satuan_kecil, b.satuan_besar,
                    ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                    CAST(ds.jml_sat_kcl AS FLOAT) AS jml_sat_kcl,
                    ISNULL(wa.wa_harga_modal, 0) AS wa_harga_modal
                FROM {$t['stok_v']} ds
                LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                LEFT JOIN (
                    SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT kode_brg, harga_satuan, ppn, discount, jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']}
                        WHERE kode_brg IN (
                            SELECT DISTINCT kode_brg FROM {$t['stok_v']}
                            WHERE is_active = 1 AND kode_bagian = ?
                        )
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg
                ) wa ON wa.kode_brg = ds.kode_brg
                WHERE ds.kode_bagian = ? AND ds.is_active = 1
                ORDER BY ISNULL(b.nama_brg, ds.kode_brg)";
        
        return $this->db->query($sql, array($kode_bagian, $kode_bagian))->result();
    }

    /**
     * Info unit/bagian by kode_bagian.
     */
    public function get_bagian_info($kode_bagian)
    {
        $sql = "SELECT kode_bagian, nama_bagian FROM mt_bagian WHERE kode_bagian = ?";
        return $this->db->query($sql, array($kode_bagian))->row();
    }

    /**
     * Semua item stok (seluruh bagian) untuk laporan cetak.
     * Returns rows ordered by nama_bagian, nama_brg.
     * Kolom: kode_bagian, nama_bagian, kode_brg, nama_brg,
     *         satuan_kecil, satuan_besar, rasio,
     *         jml_sat_kcl, wa_harga_modal
     */
    public function get_laporan_items($flag = 'medis', $tgl_filter = '')
    {
        $t       = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $sql = "SELECT
                        ks.kode_bagian,
                        ISNULL(mb.nama_bagian, ks.kode_bagian) AS nama_bagian,
                        ks.kode_brg,
                        ISNULL(b.nama_brg, ks.kode_brg)        AS nama_brg,
                        b.satuan_kecil, b.satuan_besar,
                        ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                        ks.jml_sat_kcl,
                        ISNULL(wa.wa_harga_modal, 0) AS wa_harga_modal
                    FROM (
                        /* stok_akhir aktual per item per bagian s.d. tgl_filter
                           (tanpa lower bound, konsisten dengan get_laporan_detail_items) */
                        SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                        FROM (
                            SELECT kode_brg, kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE tgl_input < '{$dr['upper']}'
                        ) ki
                        WHERE ki.rn = 1 AND ki.stok_akhir > 0
                    ) ks
                    LEFT JOIN {$t['barang']} b  ON b.kode_brg    = ks.kode_brg
                    LEFT JOIN mt_bagian      mb ON mb.kode_bagian = ks.kode_bagian
                    LEFT JOIN (
                        /* WA 3 PO terakhir s.d. tgl_filter, tanpa lower bound */
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']} d
                            JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                            WHERE p.tgl_po <= '{$tgl_filter}'
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ks.kode_brg
                    WHERE wa.wa_harga_modal > 0
                    ORDER BY ISNULL(mb.nama_bagian, ks.kode_bagian), ISNULL(b.nama_brg, ks.kode_brg)";
            // echo $sql; die;
            return $this->db->query($sql)->result();
        }

        $sql = "SELECT
                    ds.kode_bagian,
                    ISNULL(mb.nama_bagian, ds.kode_bagian) AS nama_bagian,
                    ds.kode_brg,
                    ISNULL(b.nama_brg, ds.kode_brg)        AS nama_brg,
                    b.satuan_kecil, b.satuan_besar,
                    ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                    CAST(ds.jml_sat_kcl AS FLOAT) AS jml_sat_kcl,
                    ISNULL(wa.wa_harga_modal, 0) AS wa_harga_modal
                FROM {$t['stok_v']} ds
                LEFT JOIN {$t['barang']} b  ON b.kode_brg    = ds.kode_brg
                LEFT JOIN mt_bagian      mb ON mb.kode_bagian = ds.kode_bagian
                LEFT JOIN (
                    SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT kode_brg, harga_satuan, ppn, discount, jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']}
                        WHERE kode_brg IN (
                            SELECT DISTINCT kode_brg FROM {$t['stok_v']} WHERE is_active = 1
                        )
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg
                ) wa ON wa.kode_brg = ds.kode_brg
                WHERE ds.is_active = 1
                ORDER BY ISNULL(mb.nama_bagian, ds.kode_bagian), ISNULL(b.nama_brg, ds.kode_brg)";

        return $this->db->query($sql)->result();
    }

    /**
     * Laporan detail mutasi stok per unit (saldo awal, pembelian, penerimaan,
     * penjualan, saldo akhir) untuk satu kode_bagian dalam rentang tanggal.
     *
     * Kolom yang dikembalikan:
     *   kode_brg, nama_brg, satuan_kecil, satuan_besar, rasio,
     *   saldo_awal      – stok akhir terbaru sebelum tgl_dari (sat. kecil)
     *   qty_pembelian   – neto: jenis 1  − (2 + 21)   dalam [tgl_dari, tgl_sampai]
     *   nilai_pembelian – total nilai PO dalam [tgl_dari, tgl_sampai] untuk item tsb.
     *   qty_penerimaan  – neto: (3+5) − 4              dalam [tgl_dari, tgl_sampai]
     *   qty_penjualan   – neto: (14+24+6+7) − (8+23)  dalam [tgl_dari, tgl_sampai]
     *   wa_harga_modal  – WA per satuan besar (3 PO terakhir s.d. tgl_sampai)
     */
    public function get_laporan_detail_items(
        $kode_bagian,
        $flag       = 'medis',
        $tgl_dari   = '',
        $tgl_sampai = ''
    ) {
        $t = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        // Defaults: bulan berjalan
        if (empty($tgl_sampai)) $tgl_sampai = date('Y-m-d');
        if (empty($tgl_dari))   $tgl_dari   = date('Y-m-01');

        $sql = "
            SELECT
                ab.kode_brg,
                ISNULL(b.nama_brg, ab.kode_brg)               AS nama_brg,
                b.satuan_kecil,
                b.satuan_besar,
                ISNULL(NULLIF(CAST(b.content AS INT), 0), 1)  AS rasio,
                ISNULL(sa.saldo_awal,     0)  AS saldo_awal,
                ISNULL(pb.qty_pembelian,  0)  AS qty_pembelian,
                ISNULL(pb.qty_pembelian,  0)
                    * ISNULL(wa.wa_harga_modal, 0)
                    / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1) AS nilai_pembelian,
                ISNULL(pn.qty_penerimaan, 0)  AS qty_penerimaan,
                ISNULL(pj.qty_penjualan,  0)  AS qty_penjualan,
                ISNULL(wa.wa_harga_modal,  0)  AS wa_harga_modal,
                ISNULL(sakh.saldo_akhir,   0)  AS saldo_akhir
            FROM (
                /* Semua kode_brg yang pernah ada di bagian ini s.d. tgl_sampai */
                SELECT DISTINCT kode_brg
                FROM {$t['kartu']}
                WHERE kode_bagian = ? AND tgl_input <= '{$tgl_sampai}'
            ) ab
            LEFT JOIN {$t['barang']} b ON b.kode_brg = ab.kode_brg

            /* ── WA harga modal (3 PO terakhir s.d. tgl_sampai) ── */
            LEFT JOIN (
                SELECT kode_brg, {$wa_calc}
                FROM (
                    SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                           ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                    FROM {$t['po_det']} d
                    JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                    WHERE d.kode_brg IN (
                        SELECT DISTINCT kode_brg FROM {$t['kartu']}
                        WHERE kode_bagian = ? AND tgl_input <= '{$tgl_sampai}'
                    )
                      AND p.tgl_po <= '{$tgl_sampai}'
                ) x WHERE rn <= 3
                GROUP BY kode_brg
            ) wa ON wa.kode_brg = ab.kode_brg

            /* ── Saldo Awal: stok akhir kartu terbaru sebelum tgl_dari ── */
            LEFT JOIN (
                SELECT kode_brg, CAST(stok_akhir AS FLOAT) AS saldo_awal
                FROM (
                    SELECT kode_brg, stok_akhir,
                           ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_kartu DESC) AS rn
                    FROM {$t['kartu']}
                    WHERE kode_bagian = ? AND tgl_input < '{$tgl_dari}'
                ) x WHERE rn = 1
            ) sa ON sa.kode_brg = ab.kode_brg

            /* ── Pembelian: jenis 1 − (2 + 21) ── */
            LEFT JOIN (
                SELECT kode_brg,
                       SUM(CAST(pemasukan AS FLOAT) - CAST(pengeluaran AS FLOAT)) AS qty_pembelian
                FROM {$t['kartu']}
                WHERE kode_bagian = ?
                  AND jenis_transaksi IN (1, 2, 21)
                  AND tgl_input >= '{$tgl_dari}' AND tgl_input <= '{$tgl_sampai}'
                GROUP BY kode_brg
            ) pb ON pb.kode_brg = ab.kode_brg

            /* ── Penerimaan: (3 + 5) − 4 ── */
            LEFT JOIN (
                SELECT kode_brg,
                       SUM(CAST(pemasukan AS FLOAT) - CAST(pengeluaran AS FLOAT)) AS qty_penerimaan
                FROM {$t['kartu']}
                WHERE kode_bagian = ?
                  AND jenis_transaksi IN (3, 4, 5)
                  AND tgl_input >= '{$tgl_dari}' AND tgl_input <= '{$tgl_sampai}'
                GROUP BY kode_brg
            ) pn ON pn.kode_brg = ab.kode_brg

            /* ── Penjualan: (14 + 24 + 6 + 7) − (8 + 23) → nilai positif ── */
            LEFT JOIN (
                SELECT kode_brg,
                       SUM(CAST(pengeluaran AS FLOAT) - CAST(pemasukan AS FLOAT)) AS qty_penjualan
                FROM {$t['kartu']}
                WHERE kode_bagian = ?
                  AND jenis_transaksi IN (14, 24, 6, 7, 8, 23)
                  AND tgl_input >= '{$tgl_dari}' AND tgl_input <= '{$tgl_sampai}'
                GROUP BY kode_brg
            ) pj ON pj.kode_brg = ab.kode_brg

            /* ── Saldo Akhir Aktual: stok akhir kartu terbaru s.d. tgl_sampai ── */
            LEFT JOIN (
                SELECT kode_brg, CAST(stok_akhir AS FLOAT) AS saldo_akhir
                FROM (
                    SELECT kode_brg, stok_akhir,
                           ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_kartu DESC) AS rn
                    FROM {$t['kartu']}
                    WHERE kode_bagian = ? AND tgl_input <= '{$tgl_sampai}'
                ) x WHERE rn = 1
            ) sakh ON sakh.kode_brg = ab.kode_brg

            WHERE ISNULL(sa.saldo_awal,     0) > 0
               OR ISNULL(pb.qty_pembelian,  0) <> 0
               OR ISNULL(pn.qty_penerimaan, 0) <> 0
               OR ISNULL(pj.qty_penjualan,  0) <> 0
               OR ISNULL(sakh.saldo_akhir,  0) > 0

            ORDER BY ISNULL(b.nama_brg, ab.kode_brg)
        ";
        // echo $sql; die;
        return $this->db->query($sql, array(
            $kode_bagian,  // ab
            $kode_bagian,  // wa inner
            $kode_bagian,  // sa
            $kode_bagian,  // pb
            $kode_bagian,  // pn
            $kode_bagian,  // pj
            $kode_bagian,  // sakh
        ))->result();
    }


    /**
     * Financial summary (nilai Rp) per kode_bagian — batch query untuk semua
     * bagian yang tampil di satu halaman DataTable.
     *
     * Kolom yang dikembalikan per baris:
     *   kode_bagian, saldo_awal_nilai, pembelian_nilai,
     *   penerimaan_nilai, penjualan_nilai
     *
     * Nilai dihitung dengan WA harga modal (3 PO terakhir s.d. tgl_sampai).
     * Returns assoc array keyed by kode_bagian.
     */
    public function get_financial_summary_for_bagians(
        array $kode_bagian_list,
        $flag       = 'medis',
        $tgl_dari   = '',
        $tgl_sampai = ''
    ) {
        if (empty($kode_bagian_list)) return array();

        $t       = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        if (empty($tgl_sampai)) $tgl_sampai = date('Y-m-d');
        if (empty($tgl_dari))   $tgl_dari   = date('Y-m-01');

        $n  = count($kode_bagian_list);
        $ph = implode(',', array_fill(0, $n, '?'));

        // 5 copies: ab, wa-inner, sa, mv (pb+pn+pj merged), sakh
        $params = array_merge(
            $kode_bagian_list,
            $kode_bagian_list,
            $kode_bagian_list,
            $kode_bagian_list,
            $kode_bagian_list
        );

        $sql = "
            SELECT
                ab.kode_bagian,
                SUM(ISNULL(sa.saldo_awal,      0) *
                    ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                ) AS saldo_awal_nilai,
                SUM(ISNULL(mv.qty_pembelian,   0) *
                    ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                ) AS pembelian_nilai,
                SUM(ISNULL(mv.qty_penerimaan,  0) *
                    ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                ) AS penerimaan_nilai,
                SUM(ISNULL(mv.qty_penjualan,   0) *
                    ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                ) AS penjualan_nilai,
                SUM(ISNULL(sakh.saldo_akhir,   0) *
                    ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                ) AS saldo_akhir_nilai
            FROM (
                SELECT DISTINCT kode_brg, kode_bagian
                FROM {$t['kartu']}
                WHERE kode_bagian IN ({$ph}) AND tgl_input <= '{$tgl_sampai}'
            ) ab
            LEFT JOIN {$t['barang']} b ON b.kode_brg = ab.kode_brg

            /* WA harga modal (3 PO terakhir s.d. tgl_sampai) */
            LEFT JOIN (
                SELECT kode_brg, {$wa_calc}
                FROM (
                    SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                           ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                    FROM {$t['po_det']} d
                    JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                    INNER JOIN (
                        SELECT DISTINCT kode_brg FROM {$t['kartu']}
                        WHERE kode_bagian IN ({$ph}) AND tgl_input <= '{$tgl_sampai}'
                    ) ab_items ON ab_items.kode_brg = d.kode_brg
                    WHERE p.tgl_po <= '{$tgl_sampai}'
                ) x WHERE rn <= 3
                GROUP BY kode_brg
            ) wa ON wa.kode_brg = ab.kode_brg

            /* Saldo Awal: stok akhir terbaru sebelum tgl_dari */
            LEFT JOIN (
                SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS saldo_awal
                FROM (
                    SELECT kode_brg, kode_bagian, stok_akhir,
                           ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                    FROM {$t['kartu']}
                    WHERE kode_bagian IN ({$ph}) AND tgl_input < '{$tgl_dari}'
                ) x WHERE rn = 1
            ) sa ON sa.kode_brg = ab.kode_brg AND sa.kode_bagian = ab.kode_bagian

            /* Mutasi [tgl_dari, tgl_sampai]: pembelian + penerimaan + penjualan dalam satu scan */
            LEFT JOIN (
                SELECT kode_brg, kode_bagian,
                       SUM(CASE WHEN jenis_transaksi IN (1,2,21)
                               THEN CAST(pemasukan AS FLOAT) - CAST(pengeluaran AS FLOAT)
                               ELSE 0 END) AS qty_pembelian,
                       SUM(CASE WHEN jenis_transaksi IN (3,4,5)
                               THEN CAST(pemasukan AS FLOAT) - CAST(pengeluaran AS FLOAT)
                               ELSE 0 END) AS qty_penerimaan,
                       SUM(CASE WHEN jenis_transaksi IN (6,7,8,14,23,24)
                               THEN CAST(pengeluaran AS FLOAT) - CAST(pemasukan AS FLOAT)
                               ELSE 0 END) AS qty_penjualan
                FROM {$t['kartu']}
                WHERE kode_bagian IN ({$ph})
                  AND jenis_transaksi IN (1,2,3,4,5,6,7,8,14,21,23,24)
                  AND tgl_input >= '{$tgl_dari}' AND tgl_input <= '{$tgl_sampai}'
                GROUP BY kode_brg, kode_bagian
            ) mv ON mv.kode_brg = ab.kode_brg AND mv.kode_bagian = ab.kode_bagian

            /* Saldo Akhir Aktual: stok akhir kartu terbaru s.d. tgl_sampai */
            LEFT JOIN (
                SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS saldo_akhir
                FROM (
                    SELECT kode_brg, kode_bagian, stok_akhir,
                           ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                    FROM {$t['kartu']}
                    WHERE kode_bagian IN ({$ph}) AND tgl_input <= '{$tgl_sampai}'
                ) x WHERE rn = 1
            ) sakh ON sakh.kode_brg = ab.kode_brg AND sakh.kode_bagian = ab.kode_bagian

            WHERE ISNULL(sa.saldo_awal,      0) > 0
               OR ISNULL(mv.qty_pembelian,   0) <> 0
               OR ISNULL(mv.qty_penerimaan,  0) <> 0
               OR ISNULL(mv.qty_penjualan,   0) <> 0
               OR ISNULL(sakh.saldo_akhir,   0) > 0
            GROUP BY ab.kode_bagian
        ";
        // echo $sql; die;
        $rows   = $this->db->query($sql, $params)->result();
        $result = array();
        foreach ($rows as $r) {
            $result[$r->kode_bagian] = $r;
        }
        return $result;
    }

    /**
     * Ambil info stok terakhir satu item (real-time) dari kartu stok.
     * Dipakai untuk modal konfirmasi pengosongan stok.
     *
     * Mengembalikan array atau null jika tidak ditemukan.
     */
    public function get_last_stok_item($kode_brg, $kode_bagian, $flag = 'medis')
    {
        $t       = $this->_get_tables($flag);
        $wa_calc = $this->_wa_calc();

        $sql = "
            SELECT
                ks.id_kartu,
                ks.kode_brg,
                ks.kode_bagian,
                ISNULL(b.nama_brg, ks.kode_brg)              AS nama_brg,
                b.satuan_kecil,
                b.satuan_besar,
                ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                CAST(ks.stok_akhir AS FLOAT)                  AS stok_akhir,
                ks.tgl_input,
                ISNULL(wa.wa_harga_modal, 0)                  AS wa_harga_modal
            FROM (
                SELECT TOP 1
                    id_kartu, kode_brg, kode_bagian,
                    CAST(stok_akhir AS FLOAT) AS stok_akhir,
                    tgl_input
                FROM {$t['kartu']}
                WHERE kode_brg = ? AND kode_bagian = ?
                ORDER BY id_kartu DESC
            ) ks
            LEFT JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
            LEFT JOIN (
                SELECT kode_brg, {$wa_calc}
                FROM (
                    SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                           ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                    FROM {$t['po_det']} d
                    JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                    WHERE d.kode_brg = ?
                ) x WHERE rn <= 3
                GROUP BY kode_brg
            ) wa ON wa.kode_brg = ks.kode_brg
        ";

        $row = $this->db->query($sql, array($kode_brg, $kode_bagian, $kode_brg))->row();
        if (!$row) return null;

        $rasio       = (!empty($row->rasio) && (int)$row->rasio > 0) ? (int)$row->rasio : 1;
        $wa_besar    = (float)$row->wa_harga_modal;
        $harga_kecil = ($wa_besar > 0) ? $wa_besar / $rasio : 0;
        $stok_akhir  = (float)$row->stok_akhir;
        $total_nilai = $stok_akhir * $harga_kecil;

        return array(
            'id_kartu'       => (int)$row->id_kartu,
            'kode_brg'       => trim($row->kode_brg),
            'nama_brg'       => $row->nama_brg,
            'satuan_kecil'   => $row->satuan_kecil,
            'satuan_besar'   => $row->satuan_besar,
            'rasio'          => $rasio,
            'stok_akhir'     => $stok_akhir,
            'tgl_input'      => $row->tgl_input,
            'wa_harga_modal' => $wa_besar,
            'harga_kecil'    => $harga_kecil,
            'total_nilai'    => $total_nilai,
        );
    }

    /**
     * Kosongkan stok satu item pada satu unit:
     *   1. INSERT ke tc_kartu_stok (jenis_transaksi=10, pengeluaran=stok_akhir, stok_akhir=0)
     *   2. UPDATE mt_depo_stok  → jml_sat_kcl = 0
     *   3. UPDATE mt_rekap_stok → jml_sat_kcl = 0 (hanya berdampak jika unit adalah gudang)
     *
     * Mengembalikan array ['status', 'message', ...].
     */
    public function kosongkan_stok_item(
        $kode_brg,
        $kode_bagian,
        $flag        = 'medis',
        $keterangan  = ''
    ) {
        $t        = $this->_get_tables($flag);
        $t_depo   = ($flag === 'non_medis') ? 'mt_depo_stok_nm'  : 'mt_depo_stok';
        $t_rekap  = ($flag === 'non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok';

        /* ── 1. Ambil stok terakhir dari kartu ── */
        $sql_last = "SELECT TOP 1 id_kartu, CAST(stok_akhir AS FLOAT) AS stok_akhir
                     FROM {$t['kartu']}
                     WHERE kode_brg = ? AND kode_bagian = ?
                     ORDER BY id_kartu DESC";
        $last = $this->db->query($sql_last, array($kode_brg, $kode_bagian))->row();

        if (!$last) {
            return array('status' => 404, 'message' => 'Data stok tidak ditemukan untuk item ini');
        }

        $current_stok = (float)$last->stok_akhir;

        if ($current_stok <= 0) {
            return array('status' => 422, 'message' => 'Stok sudah kosong (stok = 0), tidak perlu dikosongkan');
        }

        /* ── 2. Generate id_kartu berikutnya (MAX + 1) ── */
        $sql_id  = "SELECT ISNULL(MAX(id_kartu), 0) + 1 AS next_id FROM {$t['kartu']}";
        $id_row  = $this->db->query($sql_id)->row();
        $new_id  = (int)$id_row->next_id;

        /* ── 3. Info user dari session ── */
        $CI      = &get_instance();
        $user    = $CI->session->userdata('user');
        $user_id = ($user && isset($user->user_id)) ? (int)$user->user_id : 0;

        $ket_final = 'Pengosongan stok unit'
            . ($keterangan ? ' - ' . trim($keterangan) : '');

        /* ── 4. Jalankan dalam satu transaksi ── */
        $this->db->trans_begin();

        $this->db->insert($t['kartu'], array(
            'id_kartu'        => $new_id,
            'kode_brg'        => $kode_brg,
            'stok_awal'       => $current_stok,
            'pemasukan'       => 0,
            'pengeluaran'     => $current_stok,
            'stok_akhir'      => 0,
            'jenis_transaksi' => 10,
            'kode_bagian'     => $kode_bagian,
            'keterangan'      => $ket_final,
            'petugas'         => $user_id,
            'tgl_input'       => date('Y-m-d H:i:s'),
        ));

        /* Update mt_depo_stok (per item per bagian) */
        $this->db->update(
            $t_depo,
            array('jml_sat_kcl' => 0, 'id_kartu' => $new_id),
            array('kode_brg' => $kode_brg, 'kode_bagian' => $kode_bagian)
        );

        /* Update mt_rekap_stok (hanya berdampak jika unit = gudang utama;
           0 rows affected untuk unit/depo biasa = tidak error) */
        $this->db->update(
            $t_rekap,
            array('jml_sat_kcl' => 0),
            array('kode_brg' => $kode_brg, 'kode_bagian_gudang' => $kode_bagian)
        );

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array('status' => 500, 'message' => 'Gagal menyimpan perubahan ke database');
        }

        $this->db->trans_commit();

        return array(
            'status'        => 200,
            'message'       => 'Stok berhasil dikosongkan',
            'stok_sebelum'  => $current_stok,
            'id_kartu_baru' => $new_id,
        );
    }

}

/* End of file Persediaan_barang_rekap_per_unit_model.php */
