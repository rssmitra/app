<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 |-------------------------------------------------------
 |  MSSQL Pagination Helper for CodeIgniter Query Builder
 |  using ROW_NUMBER()
 |-------------------------------------------------------
 |
 | Cara pakai:
 |
 | $this->db->select('a,b,c');
 | $this->db->from('table');
 | $this->db->where(...);
 | $this->db->order_by('nama_pegawai', 'ASC');
 |
 | $result = mssql_qb_limit($this->db, $limit, $offset);
 |
 */

if (!function_exists('mssql_qb_limit')) {

    function mssql_qb_limit($db, $limit, $offset = 0)
    {
        // Ambil SQL hasil Query Builder
        $sql = $db->get_compiled_select();

        // Detect ORDER BY dari Query Builder (wajib)
        if (stripos($sql, 'ORDER BY') !== false) {

            // Ambil bagian ORDER BY
            $orderBy = trim(substr($sql, stripos($sql, 'ORDER BY') + 8));

            // Hapus ORDER BY dari query utama
            $sqlNoOrder = preg_replace('/ORDER BY[\s\S]+$/i', '', $sql);

        } else {
            // fallback jika dev lupa order_by()
            $orderBy = '1';
            $sqlNoOrder = $sql;
        }

        // Hitung batas awal–akhir
        $start = $offset + 1;
        $end   = $offset + $limit;

        // Bangun ulang query ROW_NUMBER paging
        $finalQuery = "
            SELECT * FROM (
                SELECT
                    ROW_NUMBER() OVER (ORDER BY $orderBy) AS row_num,
                    *
                FROM (
                    $sqlNoOrder
                ) AS base_query
            ) AS paginated
            WHERE row_num BETWEEN $start AND $end
        ";

        return $db->query($finalQuery)->result();
    }
}
