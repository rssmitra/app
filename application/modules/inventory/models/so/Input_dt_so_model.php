<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_dt_so_model extends CI_Model {

    // ── Medis ────────────────────────────────────────────────────────────────
    var $table    = 'mt_depo_stok_v';
    var $column   = array('mt_depo_stok_v.nama_brg');
    var $select   = 'kode_depo_stok, mt_depo_stok_v.kode_brg, nama_brg, mt_depo_stok_v.kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil, satuan_besar, mt_golongan.nama_golongan, mt_sub_golongan.nama_sub_golongan, nama_jenis, nama_layanan, nama_petugas, tgl_stok_opname, stok_exp, stok_sekarang, stok_sebelum, path_image, agenda_so.status_so, agenda_so.stok_adjustment, agenda_so.klarifikasi_stok';

    // ── Non-Medis ─────────────────────────────────────────────────────────────
    var $table_nm  = 'mt_depo_stok_nm_v';
    var $column_nm = array('mt_depo_stok_nm_v.nama_brg');
    var $select_nm = 'kode_depo_stok, mt_depo_stok_nm_v.kode_brg, nama_brg, mt_depo_stok_nm_v.kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil, satuan_besar, nama_petugas, tgl_stok_opname, stok_exp, stok_sekarang, stok_sebelum, path_image, agenda_so.status_so, agenda_so.stok_adjustment, agenda_so.klarifikasi_stok';

    // Cache frozen_time per agenda_so_id to avoid repeated lookups within a request
    private $_frozen_time_cache = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // =========================================================================
    // Shared helpers
    // =========================================================================

    /**
     * Returns the frozen_time string for the given agenda if is_frozen='Y', else NULL.
     * Cached in $_frozen_time_cache so successive calls within one request cost nothing.
     */
    private function _get_frozen_time($agenda_so_id)
    {
        if (!isset($this->_frozen_time_cache[$agenda_so_id])) {
            $row = $this->db->select('frozen_time, is_frozen')
                            ->get_where('tc_stok_opname_agenda', array('agenda_so_id' => $agenda_so_id))
                            ->row();
            $this->_frozen_time_cache[$agenda_so_id] =
                ($row && $row->is_frozen === 'Y') ? $row->frozen_time : null;
        }
        return $this->_frozen_time_cache[$agenda_so_id];
    }

    // =========================================================================
    // MEDIS — DataTables queries (CTE-based raw SQL)
    // =========================================================================

    /**
     * Builds the medis DataTable SQL in two parts:
     *   'cte'    => the WITH … AS (…) block
     *   'select' => the main SELECT…FROM…WHERE body (no ORDER BY)
     *
     * Keeping them separate lets get_datatables() compose a ROW_NUMBER()-based
     * pagination wrapper that is compatible with SQL Server 2008 (ODBC Driver 11).
     * CTEs defined in the WITH clause are accessible in all nested subqueries of
     * the same statement, so the wrapper SELECT can still reference ks_combined etc.
     */
    private function _build_datatables_sql()
    {
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];
        $bag          = $this->db->escape_str($_GET['bag']);
        $cutoff_raw   = (isset($_GET['cutoff']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['cutoff']))
                        ? $this->db->escape_str($_GET['cutoff']) : null;
        $frozen_time  = $this->_get_frozen_time($agenda_so_id);

        $need_ks = ($frozen_time !== null || $cutoff_raw !== null);

        // ── CTE block ────────────────────────────────────────────────────────
        $cte_parts = array();

        if ($need_ks) {
            // Build CASE conditions; fall back to an always-false predicate when
            // one side is not available, so the SUM/MAX cleanly returns 0/NULL.
            $frozen_cond = $frozen_time
                ? "tgl_input >= CONVERT(DATETIME, '" . $this->db->escape_str($frozen_time) . "', 120)"
                : '1 = 0';
            $cutoff_cond = $cutoff_raw
                ? "tgl_input <= CONVERT(DATETIME, '$cutoff_raw 23:59:59', 120)"
                : '1 = 0';

            // Single scan: computes pergerakan totals AND finds the cutoff timestamp
            $cte_parts[] =
			"ks_combined AS (
				SELECT
					kode_brg,
					SUM(CASE WHEN $frozen_cond THEN pemasukan  ELSE 0 END) AS total_pemasukan,
					SUM(CASE WHEN $frozen_cond THEN pengeluaran ELSE 0 END) AS total_pengeluaran,
					MAX(CASE WHEN $cutoff_cond THEN tgl_input  END)         AS cutoff_tgl
				FROM tc_kartu_stok
				WHERE kode_bagian = '$bag'
				GROUP BY kode_brg
			)";

            // Only materialise ks_cutoff when a cutoff date is present
            if ($cutoff_raw) {
                $cte_parts[] =
					"ks_cutoff AS (
						SELECT ks.kode_brg, ks.stok_akhir
						FROM tc_kartu_stok ks
						INNER JOIN ks_combined c
							ON  ks.kode_brg  = c.kode_brg
							AND ks.tgl_input = c.cutoff_tgl
						WHERE ks.kode_bagian = '$bag'
					)";
				}
			}

       	 	$cte_parts[] =
			"agenda_so AS (
				SELECT kode_brg, agenda_so_id, kode_bagian, set_status_aktif, status_so,
					stok_adjustment, klarifikasi_stok, nama_petugas, tgl_stok_opname,
					stok_exp, stok_sekarang, stok_sebelum,
					stok_akhir_berjalan, selisih
				FROM tc_stok_opname
				WHERE agenda_so_id = $agenda_so_id AND kode_bagian = '$bag'
			)";

        $cte_block = 'WITH ' . implode(",\n", $cte_parts);

        // ── Column expressions ────────────────────────────────────────────────
        $pemasukan_col   = $need_ks      ? 'ISNULL(kc.total_pemasukan,   0)' : '0';
        $pengeluaran_col = $need_ks      ? 'ISNULL(kc.total_pengeluaran, 0)' : '0';
        $cutoff_col      = ($cutoff_raw) ? 'cut.stok_akhir'                  : 'NULL';

        // ── WHERE filters ─────────────────────────────────────────────────────
        $where_parts = array("v.kode_bagian = '$bag'");

        if (isset($_GET['gol']) && $_GET['gol'] != '') {
            $gol_esc = $this->db->escape_str($_GET['gol']);
            $where_parts[] = "kode_kategori = '$gol_esc'";
        }
        if (isset($_GET['rak']) && $_GET['rak'] != '') {
            $rak_esc = $this->db->escape_str($_GET['rak']);
            $where_parts[] = "rak = '$rak_esc'";
        }
        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $srch = $this->db->escape_str($_POST['search']['value']);
            $where_parts[] = "v.nama_brg LIKE '%$srch%'";
        }

        $where_clause = implode("\n  AND ", $where_parts);

        // ── Optional extra JOINs from CTEs ───────────────────────────────────
        $ks_joins = '';
        if ($need_ks) {
            $ks_joins .= "\nLEFT JOIN ks_combined kc  ON kc.kode_brg  = v.kode_brg";
        }
        if ($cutoff_raw) {
            $ks_joins .= "\nLEFT JOIN ks_cutoff   cut ON cut.kode_brg = v.kode_brg";
        }

        $inner_select =
		"SELECT
			v.is_active               AS status_aktif,
			so.set_status_aktif,
			so.agenda_so_id,
			v.kode_depo_stok,
			v.kode_brg,
			v.nama_brg,
			v.kode_bagian,
			v.nama_bagian,
			v.jml_sat_kcl,
			v.satuan_kecil,
			v.satuan_besar,
			g.nama_golongan,
			sg.nama_sub_golongan,
			v.nama_jenis,
			v.nama_layanan,
			so.nama_petugas,
			so.tgl_stok_opname,
			so.stok_exp,
			so.stok_sekarang,
			so.stok_sebelum,
			v.path_image,
			so.status_so,
			so.stok_adjustment,
			so.klarifikasi_stok,
			so.stok_akhir_berjalan,
			so.selisih,
			$pemasukan_col            AS total_pemasukan,
			$pengeluaran_col          AS total_pengeluaran,
			$cutoff_col               AS cutoff_stok
		FROM  mt_depo_stok_v        v
		JOIN  mt_golongan     g   ON g.kode_golongan = v.kode_golongan
		JOIN  mt_sub_golongan sg  ON sg.kode_sub_gol  = v.kode_sub_golongan
		LEFT JOIN agenda_so   so  ON so.kode_brg      = v.kode_brg$ks_joins
		WHERE $where_clause";

        return array('cte' => $cte_block, 'select' => $inner_select);
    }

    public function get_datatables()
    {
        $parts  = $this->_build_datatables_sql();
        $start  = (int)$_POST['start'];
        $length = (int)$_POST['length'];
        // ORDER BY uses column aliases from the inner SELECT (v.is_active → status_aktif, etc.)
        $order  = 'status_aktif DESC, nama_brg ASC, nama_golongan ASC';

        if ($length != -1) {
            // ROW_NUMBER() pagination — SQL Server 2008 compatible (no OFFSET…FETCH)
            // CTEs from the WITH clause are accessible inside nested subqueries.
            $from = $start + 1;
            $to   = $start + $length;
            $sql  = "{$parts['cte']}
		SELECT * FROM (
			SELECT *, ROW_NUMBER() OVER (ORDER BY $order) AS _rn
			FROM ({$parts['select']}) AS _q
		) AS _paged WHERE _rn BETWEEN $from AND $to ORDER BY _rn";
        } else {
            $sql = "{$parts['cte']}\n{$parts['select']}\nORDER BY $order";
        }

        // $this->db->query($sql)->result();
        // echo $this->db->last_query(); // DEBUG
		return $this->db->query($sql)->result();
    }

    /**
     * Lightweight filtered count — no JOINs to kartu stok, no CTEs.
     * Only re-applies the same WHERE/LIKE filters used by get_datatables().
     */
    public function count_filtered()
    {
        $this->db->select('COUNT(DISTINCT mt_depo_stok_v.kode_brg) AS cnt', FALSE);
        $this->db->from($this->table);
        $this->db->join('mt_golongan',     'mt_golongan.kode_golongan = mt_depo_stok_v.kode_golongan');
        $this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol = mt_depo_stok_v.kode_sub_golongan');
        $this->db->where("{$this->table}.kode_bagian", $_GET['bag']);

        if (isset($_GET['gol']) && $_GET['gol'] != '') {
            $this->db->where('kode_kategori', $_GET['gol']);
        }
        if (isset($_GET['rak']) && $_GET['rak'] != '') {
            $this->db->where('rak', $_GET['rak']);
        }

        $i = 0;
        foreach ($this->column as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                ($i === 0)
                    ? $this->db->like($item, $_POST['search']['value'])
                    : $this->db->or_like($item, $_POST['search']['value']);
            }
            $i++;
        }

        $row = $this->db->get()->row();
        return $row ? (int)$row->cnt : 0;
    }

    /** Total item count for the bagian — no JOINs, just the base view. */
    public function count_all()
    {
        $bag = $this->db->escape($_GET['bag']);
        $row = $this->db->query("SELECT COUNT(*) AS cnt FROM {$this->table} WHERE kode_bagian = $bag")->row();
        return $row ? (int)$row->cnt : 0;
    }

    // =========================================================================
    // NON-MEDIS — DataTables queries (CTE-based raw SQL)
    // =========================================================================

    private function _build_datatables_sql_nm()
    {
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];
        $bag          = $this->db->escape_str($_GET['bag']);
        $cutoff_raw   = (isset($_GET['cutoff']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['cutoff']))
                        ? $this->db->escape_str($_GET['cutoff']) : null;
        $frozen_time  = $this->_get_frozen_time($agenda_so_id);

        $need_ks = ($frozen_time !== null || $cutoff_raw !== null);

        $cte_parts = array();

        if ($need_ks) {
            $frozen_cond = $frozen_time
                ? "tgl_input >= CONVERT(DATETIME, '" . $this->db->escape_str($frozen_time) . "', 120)"
                : '1 = 0';
            $cutoff_cond = $cutoff_raw
                ? "tgl_input <= CONVERT(DATETIME, '$cutoff_raw 23:59:59', 120)"
                : '1 = 0';

            $cte_parts[] =
"ks_combined AS (
    SELECT
        kode_brg,
        SUM(CASE WHEN $frozen_cond THEN pemasukan  ELSE 0 END) AS total_pemasukan,
        SUM(CASE WHEN $frozen_cond THEN pengeluaran ELSE 0 END) AS total_pengeluaran,
        MAX(CASE WHEN $cutoff_cond THEN tgl_input  END)         AS cutoff_tgl
    FROM tc_kartu_stok_nm
    WHERE kode_bagian = '$bag'
    GROUP BY kode_brg
)";

            if ($cutoff_raw) {
                $cte_parts[] =
"ks_cutoff AS (
    SELECT ks.kode_brg, ks.stok_akhir
    FROM tc_kartu_stok_nm ks
    INNER JOIN ks_combined c
        ON  ks.kode_brg  = c.kode_brg
        AND ks.tgl_input = c.cutoff_tgl
    WHERE ks.kode_bagian = '$bag'
)";
            }
        }

        $cte_parts[] =
"agenda_so AS (
    SELECT kode_brg, agenda_so_id, kode_bagian, set_status_aktif, status_so,
           stok_adjustment, klarifikasi_stok, nama_petugas, tgl_stok_opname,
           stok_exp, stok_sekarang, stok_sebelum,
           stok_akhir_berjalan, selisih
    FROM tc_stok_opname_nm
    WHERE agenda_so_id = $agenda_so_id AND kode_bagian = '$bag'
)";

        $cte_block = 'WITH ' . implode(",\n", $cte_parts);

        $pemasukan_col   = $need_ks     ? 'ISNULL(kc.total_pemasukan,   0)' : '0';
        $pengeluaran_col = $need_ks     ? 'ISNULL(kc.total_pengeluaran, 0)' : '0';
        $cutoff_col      = $cutoff_raw  ? 'cut.stok_akhir'                  : 'NULL';

        $where_parts = array("v.kode_bagian = '$bag'");

        if (isset($_GET['gol']) && $_GET['gol'] != '') {
            $gol_esc = $this->db->escape_str($_GET['gol']);
            $where_parts[] = "kode_golongan = '$gol_esc'";
        }
        if (isset($_GET['rak']) && $_GET['rak'] != '') {
            $rak_esc = $this->db->escape_str($_GET['rak']);
            $where_parts[] = "rak = '$rak_esc'";
        }
        if (isset($_POST['search']['value']) && $_POST['search']['value'] !== '') {
            $srch = $this->db->escape_str($_POST['search']['value']);
            $where_parts[] = "v.nama_brg LIKE '%$srch%'";
        }

        $where_clause = implode("\n  AND ", $where_parts);

        $ks_joins = '';
        if ($need_ks) {
            $ks_joins .= "\nLEFT JOIN ks_combined kc  ON kc.kode_brg  = v.kode_brg";
        }
        if ($cutoff_raw) {
            $ks_joins .= "\nLEFT JOIN ks_cutoff   cut ON cut.kode_brg = v.kode_brg";
        }

        $inner_select =
"SELECT
    v.is_active               AS status_aktif,
    v.nama_sub_golongan       AS nama_golongan,
    so.set_status_aktif,
    so.agenda_so_id,
    v.kode_depo_stok,
    v.kode_brg,
    v.nama_brg,
    v.kode_bagian,
    v.nama_bagian,
    v.jml_sat_kcl,
    v.satuan_kecil,
    v.satuan_besar,
    so.nama_petugas,
    so.tgl_stok_opname,
    so.stok_exp,
    so.stok_sekarang,
    so.stok_sebelum,
    v.path_image,
    so.status_so,
    so.stok_adjustment,
    so.klarifikasi_stok,
    so.stok_akhir_berjalan,
    so.selisih,
    $pemasukan_col            AS total_pemasukan,
    $pengeluaran_col          AS total_pengeluaran,
    $cutoff_col               AS cutoff_stok
FROM  mt_depo_stok_nm_v     v
JOIN  mt_bagian       mb  ON mb.kode_bagian = v.kode_bagian
LEFT JOIN agenda_so   so  ON so.kode_brg    = v.kode_brg$ks_joins
WHERE $where_clause";

        return array('cte' => $cte_block, 'select' => $inner_select);
    }

    public function get_datatables_nm()
    {
        $parts  = $this->_build_datatables_sql_nm();
        $start  = (int)$_POST['start'];
        $length = (int)$_POST['length'];
        $order  = 'status_aktif DESC, nama_brg ASC, nama_golongan ASC';

        if ($length != -1) {
            $from = $start + 1;
            $to   = $start + $length;
            $sql  = "{$parts['cte']}
SELECT * FROM (
    SELECT *, ROW_NUMBER() OVER (ORDER BY $order) AS _rn
    FROM ({$parts['select']}) AS _q
) AS _paged WHERE _rn BETWEEN $from AND $to ORDER BY _rn";
        } else {
            $sql = "{$parts['cte']}\n{$parts['select']}\nORDER BY $order";
        }

        return $this->db->query($sql)->result();
    }

    public function count_filtered_nm()
    {
        $this->db->select('COUNT(*) AS cnt', FALSE);
        $this->db->from($this->table_nm);
        $this->db->where('mt_depo_stok_nm_v.kode_bagian', $_GET['bag']);

        if (isset($_GET['gol']) && $_GET['gol'] != '') {
            $this->db->where('kode_golongan', $_GET['gol']);
        }
        if (isset($_GET['rak']) && $_GET['rak'] != '') {
            $this->db->where('rak', $_GET['rak']);
        }

        $i = 0;
        foreach ($this->column_nm as $item) {
            if (isset($_POST['search']['value']) && $_POST['search']['value']) {
                ($i === 0)
                    ? $this->db->like($item, $_POST['search']['value'])
                    : $this->db->or_like($item, $_POST['search']['value']);
            }
            $i++;
        }

        $row = $this->db->get()->row();
        return $row ? (int)$row->cnt : 0;
    }

    public function count_all_nm()
    {
        $bag = $this->db->escape($_GET['bag']);
        $row = $this->db->query("SELECT COUNT(*) AS cnt FROM {$this->table_nm} WHERE kode_bagian = $bag")->row();
        return $row ? (int)$row->cnt : 0;
    }

    // =========================================================================
    // Agenda helper
    // =========================================================================

    public function get_agenda_by_id($agenda_so_id)
    {
        return $this->db->get_where('tc_stok_opname_agenda', array('agenda_so_id' => $agenda_so_id))->row();
    }

    // =========================================================================
    // DRAFT SAVE — only writes to tc_stok_opname / tc_stok_opname_nm
    // =========================================================================

    public function save_draft_medis()
    {
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];

        $fld = array(
            'agenda_so_id'             => $agenda_so_id,
            'kode_bagian'              => $_POST['kode_bagian'],
            'kode_brg'                 => $_POST['kode_brg'],
            'stok_sekarang'            => (isset($_POST['stok_opname']) && $_POST['stok_opname'] !== '') ? (int)$_POST['stok_opname'] : null,
            'stok_exp'                 => isset($_POST['stok_exp'])              ? (int)$_POST['stok_exp']              : 0,
            'stok_adjustment'          => isset($_POST['stok_adjustment'])       ? (int)$_POST['stok_adjustment']       : 0,
            'klarifikasi_stok'         => isset($_POST['klarifikasi_stok'])      ? $_POST['klarifikasi_stok']           : null,
            'set_status_aktif'         => isset($_POST['status_aktif'])          ? (int)$_POST['status_aktif']          : 1,
            'nama_petugas'             => $this->session->userdata('session_input_so')['nama_pegawai'],
            'tgl_stok_opname'          => date('Y-m-d H:i:s'),
            'status_so'                => 'draft',
            // Snapshot + calculated fields
            'stok_akhir_berjalan'      => isset($_POST['stok_akhir_berjalan'])   ? (int)$_POST['stok_akhir_berjalan']  : null,
            'total_pemasukan'          => isset($_POST['total_pemasukan'])        ? (int)$_POST['total_pemasukan']      : 0,
            'total_pengeluaran'        => isset($_POST['total_pengeluaran'])      ? (int)$_POST['total_pengeluaran']    : 0,
            'selisih'                  => isset($_POST['selisih'])                ? (int)$_POST['selisih']              : null,
            'stok_final'               => isset($_POST['stok_final'])             ? (int)$_POST['stok_final']           : null,
        );

        $cek = $this->cek_input_stok_before('tc_stok_opname', array(
            'kode_brg'     => $_POST['kode_brg'],
            'kode_bagian'  => $_POST['kode_bagian'],
            'agenda_so_id' => $agenda_so_id,
        ));

        if ($cek->num_rows() > 0) {
            $this->db->update('tc_stok_opname', $fld, array(
                'kode_brg'     => $_POST['kode_brg'],
                'kode_bagian'  => $_POST['kode_bagian'],
                'agenda_so_id' => $agenda_so_id,
            ));
        } else {
            $fld['stok_sebelum']             = $this->_get_cutoff_stok($_POST['kode_brg'], $_POST['kode_bagian'], $agenda_so_id, 'tc_kartu_stok');
            $fld['harga_pembelian_terakhir'] = $this->_get_harga_medis($_POST['kode_brg']);
            $this->db->insert('tc_stok_opname', $fld);
        }

        return true;
    }

    public function save_draft_nm()
    {
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];

        $fld = array(
            'agenda_so_id'             => $agenda_so_id,
            'kode_bagian'              => $_POST['kode_bagian'],
            'kode_brg'                 => $_POST['kode_brg'],
            'stok_sekarang'            => (isset($_POST['stok_opname']) && $_POST['stok_opname'] !== '') ? (int)$_POST['stok_opname'] : null,
            'stok_exp'                 => isset($_POST['stok_exp'])              ? (int)$_POST['stok_exp']              : 0,
            'stok_adjustment'          => isset($_POST['stok_adjustment'])       ? (int)$_POST['stok_adjustment']       : 0,
            'klarifikasi_stok'         => isset($_POST['klarifikasi_stok'])      ? $_POST['klarifikasi_stok']           : null,
            'set_status_aktif'         => isset($_POST['status_aktif'])          ? (int)$_POST['status_aktif']          : 1,
            'nama_petugas'             => $this->session->userdata('session_input_so')['nama_pegawai'],
            'tgl_stok_opname'          => date('Y-m-d H:i:s'),
            'status_so'                => 'draft',
            // Snapshot + calculated fields
            'stok_akhir_berjalan'      => isset($_POST['stok_akhir_berjalan'])   ? (int)$_POST['stok_akhir_berjalan']  : null,
            'total_pemasukan'          => isset($_POST['total_pemasukan'])        ? (int)$_POST['total_pemasukan']      : 0,
            'total_pengeluaran'        => isset($_POST['total_pengeluaran'])      ? (int)$_POST['total_pengeluaran']    : 0,
            'selisih'                  => isset($_POST['selisih'])                ? (int)$_POST['selisih']              : null,
            'stok_final'               => isset($_POST['stok_final'])             ? (int)$_POST['stok_final']           : null,
        );

        $cek = $this->cek_input_stok_before('tc_stok_opname_nm', array(
            'kode_brg'     => $_POST['kode_brg'],
            'kode_bagian'  => $_POST['kode_bagian'],
            'agenda_so_id' => $agenda_so_id,
        ));

        if ($cek->num_rows() > 0) {
            $this->db->update('tc_stok_opname_nm', $fld, array(
                'kode_brg'     => $_POST['kode_brg'],
                'kode_bagian'  => $_POST['kode_bagian'],
                'agenda_so_id' => $agenda_so_id,
            ));
        } else {
            $fld['stok_sebelum']             = $this->_get_cutoff_stok($_POST['kode_brg'], $_POST['kode_bagian'], $agenda_so_id, 'tc_kartu_stok_nm');
            $fld['harga_pembelian_terakhir'] = $this->_get_harga_nm($_POST['kode_brg']);
            $this->db->insert('tc_stok_opname_nm', $fld);
        }

        return true;
    }

    // =========================================================================
    // FINAL SAVE — writes SO record + triggers inventory mutation
    // =========================================================================

    public function save_final_medis()
    {
        $this->load->library('inventory_lib');
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];

        // Safeguard: skip mutation if already finalised
        $cek = $this->cek_input_stok_before('tc_stok_opname', array(
            'kode_brg'     => $_POST['kode_brg'],
            'kode_bagian'  => $_POST['kode_bagian'],
            'agenda_so_id' => $agenda_so_id,
        ));
        if ($cek->num_rows() > 0 && $cek->row()->status_so === 'final') {
            return false;
        }

        $last_stok = $this->db->get_where('mt_depo_stok_v', array(
            'kode_bagian' => $_POST['kode_bagian'],
            'kode_brg'    => $_POST['kode_brg'],
        ))->row();

        $stok_opname = (int)$_POST['stok_opname'];
        $stok_adj    = isset($_POST['stok_adjustment']) ? (int)$_POST['stok_adjustment'] : 0;
        $new_stok    = $stok_opname + $stok_adj;

        $fld = array(
            'agenda_so_id'             => $agenda_so_id,
            'kode_bagian'              => $_POST['kode_bagian'],
            'kode_brg'                 => $_POST['kode_brg'],
            'stok_sekarang'            => $stok_opname,
            'stok_exp'                 => isset($_POST['stok_exp'])              ? (int)$_POST['stok_exp']              : 0,
            'stok_adjustment'          => $stok_adj,
            'klarifikasi_stok'         => isset($_POST['klarifikasi_stok'])      ? $_POST['klarifikasi_stok']           : null,
            'set_status_aktif'         => isset($_POST['status_aktif'])          ? (int)$_POST['status_aktif']          : 1,
            'nama_petugas'             => $this->session->userdata('session_input_so')['nama_pegawai'],
            'tgl_stok_opname'          => date('Y-m-d H:i:s'),
            'status_so'                => 'final',
            'final_date'               => date('Y-m-d H:i:s'),
            // Snapshot + calculated fields
            'stok_akhir_berjalan'      => isset($_POST['stok_akhir_berjalan'])   ? (int)$_POST['stok_akhir_berjalan']  : null,
            'total_pemasukan'          => isset($_POST['total_pemasukan'])        ? (int)$_POST['total_pemasukan']      : 0,
            'total_pengeluaran'        => isset($_POST['total_pengeluaran'])      ? (int)$_POST['total_pengeluaran']    : 0,
            'selisih'                  => isset($_POST['selisih'])                ? (int)$_POST['selisih']              : null,
            'stok_final'               => isset($_POST['stok_final'])             ? (int)$_POST['stok_final']           : null,
        );

        $last_id_tc_so = null;
        if ($cek->num_rows() > 0) {
            $this->db->update('tc_stok_opname', $fld, array(
                'kode_brg'     => $_POST['kode_brg'],
                'kode_bagian'  => $_POST['kode_bagian'],
                'agenda_so_id' => $agenda_so_id,
            ));
            $last_id_tc_so = $cek->row()->id_tc_stok_opname;
        } else {
            $fld['stok_sebelum']            = $this->_get_cutoff_stok($_POST['kode_brg'], $_POST['kode_bagian'], $agenda_so_id, 'tc_kartu_stok');
            $fld['harga_pembelian_terakhir'] = $this->_get_harga_medis($_POST['kode_brg']);
            $this->db->insert('tc_stok_opname', $fld);
            $last_id_tc_so = $this->db->insert_id();
        }

        if ($new_stok >= 0) {
            $config = array(
                'id_tc_stok_opname' => $last_id_tc_so,
                'agenda_so_id'      => $agenda_so_id,
                'last_stok'         => $last_stok->jml_sat_kcl,
                'new_stok'          => $new_stok,
                'kode_bagian'       => $_POST['kode_bagian'],
                'kode_brg'          => $_POST['kode_brg'],
                'table_depo_flag'   => 'mt_depo_stok',
                'table_kartu_flag'  => 'tc_kartu_stok',
                'table'             => 'tc_kartu_stok',
                'petugas'           => $this->session->userdata('session_input_so')['nama_pegawai'],
            );
            $this->inventory_lib->save_mutasi_stok($config);
        }

        return true;
    }

    public function save_final_nm()
    {
        $this->load->library('inventory_lib');
        $agenda_so_id = (int)$this->session->userdata('session_input_so')['agenda_so_id'];

        $cek = $this->cek_input_stok_before('tc_stok_opname_nm', array(
            'kode_brg'     => $_POST['kode_brg'],
            'kode_bagian'  => $_POST['kode_bagian'],
            'agenda_so_id' => $agenda_so_id,
        ));
        if ($cek->num_rows() > 0 && $cek->row()->status_so === 'final') {
            return false;
        }

        $last_stok = $this->db->get_where('mt_depo_stok_nm_v', array(
            'kode_bagian' => $_POST['kode_bagian'],
            'kode_brg'    => $_POST['kode_brg'],
        ))->row();

        $stok_opname = (int)$_POST['stok_opname'];
        $stok_adj    = isset($_POST['stok_adjustment']) ? (int)$_POST['stok_adjustment'] : 0;
        $new_stok    = $stok_opname + $stok_adj;

        $fld = array(
            'agenda_so_id'             => $agenda_so_id,
            'kode_bagian'              => $_POST['kode_bagian'],
            'kode_brg'                 => $_POST['kode_brg'],
            'stok_sekarang'            => $stok_opname,
            'stok_exp'                 => isset($_POST['stok_exp'])              ? (int)$_POST['stok_exp']              : 0,
            'stok_adjustment'          => $stok_adj,
            'klarifikasi_stok'         => isset($_POST['klarifikasi_stok'])      ? $_POST['klarifikasi_stok']           : null,
            'set_status_aktif'         => isset($_POST['status_aktif'])          ? (int)$_POST['status_aktif']          : 1,
            'nama_petugas'             => $this->session->userdata('session_input_so')['nama_pegawai'],
            'tgl_stok_opname'          => date('Y-m-d H:i:s'),
            'status_so'                => 'final',
            // Snapshot + calculated fields
            'stok_akhir_berjalan'      => isset($_POST['stok_akhir_berjalan'])   ? (int)$_POST['stok_akhir_berjalan']  : null,
            'total_pemasukan'          => isset($_POST['total_pemasukan'])        ? (int)$_POST['total_pemasukan']      : 0,
            'total_pengeluaran'        => isset($_POST['total_pengeluaran'])      ? (int)$_POST['total_pengeluaran']    : 0,
            'selisih'                  => isset($_POST['selisih'])                ? (int)$_POST['selisih']              : null,
            'stok_final'               => isset($_POST['stok_final'])             ? (int)$_POST['stok_final']           : null,
        );

        $last_id_tc_so = null;
        if ($cek->num_rows() > 0) {
            $this->db->update('tc_stok_opname_nm', $fld, array(
                'kode_brg'     => $_POST['kode_brg'],
                'kode_bagian'  => $_POST['kode_bagian'],
                'agenda_so_id' => $agenda_so_id,
            ));
            $last_id_tc_so = $cek->row()->id_tc_stok_opname;
        } else {
            $fld['stok_sebelum']            = $this->_get_cutoff_stok($_POST['kode_brg'], $_POST['kode_bagian'], $agenda_so_id, 'tc_kartu_stok_nm');
            $fld['harga_pembelian_terakhir'] = $this->_get_harga_nm($_POST['kode_brg']);
            $this->db->insert('tc_stok_opname_nm', $fld);
            $last_id_tc_so = $this->db->insert_id();
        }

        if ($new_stok >= 0) {
            $config = array(
                'id_tc_stok_opname' => $last_id_tc_so,
                'agenda_so_id'      => $agenda_so_id,
                'last_stok'         => $last_stok->jml_sat_kcl,
                'new_stok'          => $new_stok,
                'kode_bagian'       => $_POST['kode_bagian'],
                'kode_brg'          => $_POST['kode_brg'],
                'table_depo_flag'   => 'mt_depo_stok_nm',
                'table_kartu_flag'  => 'tc_kartu_stok_nm',
                'table'             => 'tc_kartu_stok_nm',
                'petugas'           => $this->session->userdata('session_input_so')['nama_pegawai'],
            );
            $this->inventory_lib->save_mutasi_stok($config);
        }

        return true;
    }

    // =========================================================================
    // Status aktif helper
    // =========================================================================

    public function cek_input_stok_before($table, $where)
    {
        return $this->db->get_where($table, $where);
    }

    public function update_status_brg($table, $field_data, $where)
    {
        return $this->db->update($table, $field_data, $where);
    }

    // =========================================================================
    // Private helpers
    // =========================================================================

    /**
     * Returns stok_akhir from tc_kartu_stok as of the agenda's cut-off date.
     * Falls back to current jml_sat_kcl if no kartu stok entry is found.
     */
    private function _get_cutoff_stok($kode_brg, $kode_bagian, $agenda_so_id, $table_kartu)
    {
        $agenda = $this->db->select('agenda_so_cut_off_stock')
                           ->get_where('tc_stok_opname_agenda', array('agenda_so_id' => $agenda_so_id))
                           ->row();

        if ($agenda && $agenda->agenda_so_cut_off_stock) {
            $cutoff          = $agenda->agenda_so_cut_off_stock . ' 23:59:59';
            $kode_brg_esc    = $this->db->escape_str($kode_brg);
            $kode_bagian_esc = $this->db->escape_str($kode_bagian);

            $sql = "SELECT TOP 1 stok_akhir
                    FROM $table_kartu
                    WHERE kode_brg    = '$kode_brg_esc'
                      AND kode_bagian = '$kode_bagian_esc'
                      AND tgl_input  <= '$cutoff'
                    ORDER BY tgl_input DESC";
            $row = $this->db->query($sql)->row();
            if ($row) {
                return (int)$row->stok_akhir;
            }
        }

        $depo_table = ($table_kartu === 'tc_kartu_stok_nm') ? 'mt_depo_stok_nm_v' : 'mt_depo_stok_v';
        $current    = $this->db->get_where($depo_table, array('kode_brg' => $kode_brg, 'kode_bagian' => $kode_bagian))->row();
        return $current ? (int)$current->jml_sat_kcl : 0;
    }

    /**
     * Weighted average purchase price per satuan terkecil from the last 3 POs (medis).
     *
     * Formula: WA = SUM(jumlah_harga_netto) / SUM(jumlah_besar_acc) / rasio
     *
     *   - jumlah_harga_netto : total netto value per PO line (already total, not unit price)
     *   - jumlah_besar_acc   : quantity received in purchase unit (satuan besar)
     *   - rasio              : conversion factor satuan besar → satuan kecil from mt_barang
     *
     * NULLIF guards prevent division-by-zero when qty or rasio is 0.
     */
    private function _get_harga_medis($kode_brg)
    {
        $kode_brg_esc = $this->db->escape_str($kode_brg);

        $sql = "
            SELECT
                SUM(CAST(d.jumlah_harga_netto AS DECIMAL(18,4)))
                / NULLIF(SUM(CAST(d.jumlah_besar_acc AS DECIMAL(18,4))), 0)
                / NULLIF(
                    (SELECT CAST(content AS DECIMAL(18,4)) FROM mt_barang WHERE kode_brg = '$kode_brg_esc'),
                    0
                  ) AS harga_wa
            FROM (
                SELECT TOP 3 d2.jumlah_harga_netto, d2.jumlah_besar_acc
                FROM   tc_po_det d2
                JOIN   tc_po     p  ON d2.id_tc_po = p.id_tc_po
                WHERE  d2.kode_brg = '$kode_brg_esc'
                ORDER BY p.tgl_po DESC
            ) d
        ";

        $row = $this->db->query($sql)->row();
        return ($row && $row->harga_wa !== null) ? (int)round($row->harga_wa) : 0;
    }

    /** Average purchase price from last 3 POs (non-medis) */
    private function _get_harga_nm($kode_brg)
    {
        $kode_brg_esc = $this->db->escape_str($kode_brg);
        $this->db->select('AVG(CAST(harga_satuan AS INT)) as harga');
        $this->db->from('tc_po_nm_det');
        $this->db->where("id_tc_po_det IN (SELECT TOP 3 id_tc_po_det FROM tc_po_nm_det, tc_po_nm WHERE tc_po_nm_det.id_tc_po = tc_po_nm.id_tc_po AND kode_brg = '$kode_brg_esc' ORDER BY tgl_po DESC) ");
        $row = $this->db->get()->row();
        if ($row && $row->harga) {
            return (int)$row->harga;
        }
        $brg = $this->db->get_where('mt_barang_nm', array('kode_brg' => $kode_brg))->row();
        return $brg ? (int)$brg->harga_beli : 0;
    }

}
