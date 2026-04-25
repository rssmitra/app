<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_laporan_mod_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ----------------------------------------------------------------
    // LIST & HEADER
    // ----------------------------------------------------------------

    var $table  = 'tc_mod_laporan';
    var $column = ['tc_mod_laporan.tanggal','tc_mod_laporan.nama_mod','tc_mod_laporan.shift_mod','tc_mod_laporan.status'];
    var $select = 'tc_mod_laporan.id, tc_mod_laporan.tanggal, tc_mod_laporan.nama_mod, tc_mod_laporan.shift_mod, tc_mod_laporan.status, tc_mod_laporan.created_at';
    var $order  = ['tc_mod_laporan.tanggal' => 'DESC', 'tc_mod_laporan.created_at' => 'DESC'];

    private function _main_query() {
        $this->db->select($this->select);
        $this->db->from($this->table);
       
    }

    private function _get_datatables_query() {
        $this->_main_query();
        $this->db->select('tc_mod_lainnya.keterangan_lain');
         $this->db->join('tc_mod_lainnya', 'tc_mod_lainnya.laporan_id = tc_mod_laporan.id', 'left');
        $i = 0;
        foreach ($this->column as $item) {
            if (!empty($_POST['search']['value'])) {
                ($i === 0)
                    ? $this->db->like($item, $_POST['search']['value'])
                    : $this->db->or_like($item, $_POST['search']['value']);
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $cols = array_values($this->column);
            $col  = isset($cols[$_POST['order']['0']['column']]) ? $cols[$_POST['order']['0']['column']] : key($this->order);
            $this->db->order_by($col, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by(key($this->order), reset($this->order));
        }
    }

    public function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered() {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all() {
        $this->_main_query();
        return $this->db->count_all_results();
    }

    public function get_list() {
        return $this->db->query("
            SELECT id, tanggal, nama_mod, shift_mod, status, created_at
            FROM tc_mod_laporan
            ORDER BY tanggal DESC, created_at DESC
        ")->result();
    }

    public function get_header($id) {
        return $this->db->query("SELECT * FROM tc_mod_laporan WHERE id=?", [$id])->row();
    }

    public function get_all_sections($id) {
        return [
            'igd'            => $this->get_igd($id),
            'rawat_jalan'    => $this->get_rawat_jalan($id),
            'hemodialisa'    => $this->get_hemodialisa($id),
            'rawat_inap'     => $this->get_rawat_inap($id),
            'ranap_detail'   => $this->get_ranap_pengawasan($id),
            'intensive'      => $this->get_intensive($id),
            'icu_detail'     => $this->get_intensive_detail($id),
            'vk'             => $this->get_vk($id),
            'vk_detail'      => $this->get_vk_detail($id),
            'perina'         => $this->get_perina($id),
            'perina_detail'  => $this->get_perina_detail($id),
            'kamar_op'       => $this->get_kamar_operasi($id),
            'lab'            => $this->get_laboratorium($id),
            'farmasi'        => $this->get_farmasi($id),
            'radiologi'      => $this->get_radiologi($id),
            'lainnya'        => $this->get_lainnya($id),
            'fotos'          => $this->get_fotos_grouped($id),
        ];
    }

    // ----------------------------------------------------------------
    // SAVE (INSERT)
    // ----------------------------------------------------------------

    public function save($post, $status = 'final') {
        $user = $this->session->userdata('username') ?: 'system';

        // 1. Header
        $this->db->query("
            INSERT INTO tc_mod_laporan (tanggal, nama_mod, shift_mod, created_by, status, user_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ", [
            $post['tanggal'],
            strtoupper($post['nama_mod']),
            $post['shift_mod'],
            $user,
            $status,
            $this->session->userdata('user')->user_id,
        ]);
        $id = $this->db->query("SELECT SCOPE_IDENTITY() AS id")->row()->id;

        $this->_save_sections($id, $post);
        return $id;
    }

    // ----------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------

    public function update($id, $post, $status = 'final') {
        $user = $this->session->userdata('username') ?: 'system';

        $this->db->query("
            UPDATE tc_mod_laporan SET tanggal=?, nama_mod=?, shift_mod=?, status=?, updated_by=?, updated_at=GETDATE()
            WHERE id=?
        ", [
            $post['tanggal'],
            strtoupper($post['nama_mod']),
            $post['shift_mod'],
            $status,
            $user,
            $id,
        ]);

        // Delete existing child data then re-insert
        $tables = [
            'tc_mod_igd', 'tc_mod_rawat_jalan', 'tc_mod_hemodialisa',
            'tc_mod_rawat_inap', 'tc_mod_ranap_pengawasan',
            'tc_mod_intensive', 'tc_mod_intensive_detail',
            'tc_mod_vk', 'tc_mod_vk_detail', 'tc_mod_perina', 'tc_mod_perina_detail',
            'tc_mod_kamar_operasi',
            'tc_mod_laboratorium', 'tc_mod_farmasi', 'tc_mod_radiologi',
            'tc_mod_lainnya',
        ];
        foreach ($tables as $tbl) {
            $this->db->query("DELETE FROM $tbl WHERE laporan_id=?", [$id]);
        }

        $this->_save_sections($id, $post);
    }

    private function _save_sections($id, $post) {
        $p = function($key, $default = 0) use ($post) {
            return isset($post[$key]) ? $post[$key] : $default;
        };

        // 2. IGD
        $this->db->query("
            INSERT INTO tc_mod_igd (laporan_id,jml_pasien,bpjs,umum,asuransi,naker,rssm,
                ranap,doa,doe,jml_rujukan_ditolak,alasan_ditolak,jml_menolak_ranap,alasan_menolak_ranap)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('igd_jml'), $p('igd_bpjs'), $p('igd_umum'), $p('igd_asuransi'), $p('igd_naker'), $p('igd_rssm'),
            $p('igd_ranap'), $p('igd_doa'), $p('igd_doe'),
            $p('igd_jml_rujukan_ditolak'), $p('igd_alasan_ditolak',''),
            $p('igd_jml_menolak_ranap'), $p('igd_alasan_menolak_ranap',''),
        ]);

        // 3. Rawat Jalan
        $this->db->query("
            INSERT INTO tc_mod_rawat_jalan (laporan_id,jml_pasien,bpjs,umum,asuransi,naker,rssm,ranap)
            VALUES (?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('rj_jml'), $p('rj_bpjs'), $p('rj_umum'), $p('rj_asuransi'), $p('rj_naker'), $p('rj_rssm'),
            $p('rj_ranap'),
        ]);

        // 4. Hemodialisa
        $this->db->query("
            INSERT INTO tc_mod_hemodialisa (laporan_id,jml_pasien,bpjs,umum,asuransi,hd_ranap)
            VALUES (?,?,?,?,?,?)
        ", [
            $id,
            $p('hd_jml'), $p('hd_bpjs'), $p('hd_umum'), $p('hd_asuransi'), $p('hd_ranap'),
        ]);

        // 5. Rawat Inap
        $this->db->query("
            INSERT INTO tc_mod_rawat_inap (laporan_id,jml_pasien,bpjs,umum,asuransi,naker,
                rencana_operasi,jml_pengawasan,tt_vvip,tt_vip1,tt_vip2,tt_kelas1,tt_kelas2,tt_kelas3)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('ri_jml'), $p('ri_bpjs'), $p('ri_umum'), $p('ri_asuransi'), $p('ri_naker'),
            $p('ri_rencana_op'), $p('ri_jml_pengawasan'),
            $p('tt_vvip'), $p('tt_vip1'), $p('tt_vip2'),
            $p('tt_kelas1'), $p('tt_kelas2'), $p('tt_kelas3'),
        ]);

        // 5a. Detail pengawasan khusus ranap
        if (!empty($post['ranap_nama_umur'])) {
            foreach ($post['ranap_nama_umur'] as $k => $v) {
                if (empty($v)) continue;
                $this->db->query("
                    INSERT INTO tc_mod_ranap_pengawasan (laporan_id,nama_umur,jaminan,hari_rawat,diagnosa,dpjp)
                    VALUES (?,?,?,?,?,?)
                ", array(
                    $id, $v,
                    isset($post['ranap_jaminan'][$k])   ? $post['ranap_jaminan'][$k]   : '',
                    isset($post['ranap_hari_rawat'][$k])? $post['ranap_hari_rawat'][$k]: '',
                    isset($post['ranap_diagnosa'][$k])  ? $post['ranap_diagnosa'][$k]  : '',
                    isset($post['ranap_dpjp'][$k])      ? $post['ranap_dpjp'][$k]      : '',
                ));
            }
        }

        // 6. Intensive Unit
        $this->db->query("
            INSERT INTO tc_mod_intensive (laporan_id,
                icu_total,icu_bpjs,icu_umum,icu_asuransi,
                picu_total,picu_bpjs,picu_umum,picu_asuransi,
                nicu_total,nicu_bpjs,nicu_umum,nicu_asuransi)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('icu_total'), $p('icu_bpjs'), $p('icu_umum'), $p('icu_asuransi'),
            $p('picu_total'), $p('picu_bpjs'), $p('picu_umum'), $p('picu_asuransi'),
            $p('nicu_total'), $p('nicu_bpjs'), $p('nicu_umum'), $p('nicu_asuransi'),
        ]);

        // 6a. Detail pasien ICU/PICU/NICU
        if (!empty($post['icu_det_unit'])) {
            foreach ($post['icu_det_unit'] as $k => $unit) {
                if (empty($unit)) continue;
                $this->db->query("
                    INSERT INTO tc_mod_intensive_detail (laporan_id,unit,nama_umur,jaminan,hari_rawat,diagnosa,dpjp)
                    VALUES (?,?,?,?,?,?,?)
                ", array(
                    $id, $unit,
                    isset($post['icu_det_nama'][$k])     ? $post['icu_det_nama'][$k]     : '',
                    isset($post['icu_det_jaminan'][$k])  ? $post['icu_det_jaminan'][$k]  : '',
                    isset($post['icu_det_hari'][$k])     ? $post['icu_det_hari'][$k]     : '',
                    isset($post['icu_det_diagnosa'][$k]) ? $post['icu_det_diagnosa'][$k] : '',
                    isset($post['icu_det_dpjp'][$k])     ? $post['icu_det_dpjp'][$k]     : '',
                ));
            }
        }

        // 7. VK
        $this->db->query("
            INSERT INTO tc_mod_vk (laporan_id,jml_pasien,bpjs,umum,asuransi,jml_rujukan,jml_rujukan_ditolak)
            VALUES (?,?,?,?,?,?,?)
        ", [
            $id,
            $p('vk_jml'), $p('vk_bpjs'), $p('vk_umum'), $p('vk_asuransi'),
            $p('vk_jml_rujukan'), $p('vk_jml_ditolak'),
        ]);

        // 7b. VK Detail
        if (!empty($post['vk_det_nama'])) {
            foreach ($post['vk_det_nama'] as $k => $v) {
                if (empty($v)) continue;
                $this->db->query("
                    INSERT INTO tc_mod_vk_detail (laporan_id,nama_umur,jaminan,diagnosa,dpjp)
                    VALUES (?,?,?,?,?)
                ", [
                    $id, $v,
                    isset($post['vk_det_jaminan'][$k])  ? $post['vk_det_jaminan'][$k]  : '',
                    isset($post['vk_det_diagnosa'][$k]) ? $post['vk_det_diagnosa'][$k] : '',
                    isset($post['vk_det_dpjp'][$k])     ? $post['vk_det_dpjp'][$k]     : '',
                ]);
            }
        }

        // 8. Perina
        $this->db->query("
            INSERT INTO tc_mod_perina (laporan_id,jml_pasien,bpjs,umum,asuransi,jml_bayi_sakit)
            VALUES (?,?,?,?,?,?)
        ", [
            $id,
            $p('pna_jml'), $p('pna_bpjs'), $p('pna_umum'), $p('pna_asuransi'),
            $p('pna_jml_bayi_sakit'),
        ]);

        // 8b. Perina Detail
        if (!empty($post['pna_det_nama'])) {
            foreach ($post['pna_det_nama'] as $k => $v) {
                if (empty($v)) continue;
                $this->db->query("
                    INSERT INTO tc_mod_perina_detail (laporan_id,nama_umur,jaminan,diagnosa,dpjp)
                    VALUES (?,?,?,?,?)
                ", [
                    $id, $v,
                    isset($post['pna_det_jaminan'][$k])  ? $post['pna_det_jaminan'][$k]  : '',
                    isset($post['pna_det_diagnosa'][$k]) ? $post['pna_det_diagnosa'][$k] : '',
                    isset($post['pna_det_dpjp'][$k])     ? $post['pna_det_dpjp'][$k]     : '',
                ]);
            }
        }

        // 9. Kamar Operasi
        foreach (array('pagi','sore','malam') as $shift) {
            if (!empty($post['ok_'.$shift.'_nama'])) {
                foreach ($post['ok_'.$shift.'_nama'] as $k => $v) {
                    if (empty($v)) continue;
                    $this->db->query("
                        INSERT INTO tc_mod_kamar_operasi (laporan_id,shift,nama_umur,jaminan,diagnosa,dpjp,jam)
                        VALUES (?,?,?,?,?,?,?)
                    ", array(
                        $id, $shift, $v,
                        isset($post['ok_'.$shift.'_jaminan'][$k])  ? $post['ok_'.$shift.'_jaminan'][$k]  : '',
                        isset($post['ok_'.$shift.'_diagnosa'][$k]) ? $post['ok_'.$shift.'_diagnosa'][$k] : '',
                        isset($post['ok_'.$shift.'_dpjp'][$k])     ? $post['ok_'.$shift.'_dpjp'][$k]     : '',
                        isset($post['ok_'.$shift.'_jam'][$k])      ? $post['ok_'.$shift.'_jam'][$k]      : '',
                    ));
                }
            }
        }

        // 10. Laboratorium
        $this->db->query("
            INSERT INTO tc_mod_laboratorium (laporan_id,jml_pasien,bpjs,umum,asuransi,naker,rssm,patologi_klinis,patologi_anatomi)
            VALUES (?,?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('lab_jml'), $p('lab_bpjs'), $p('lab_umum'), $p('lab_asuransi'), $p('lab_naker'), $p('lab_rssm'),
            $p('lab_pk'), $p('lab_pa'),
        ]);

        // 11. Farmasi
        $this->db->query("
            INSERT INTO tc_mod_farmasi (laporan_id,jml_resep,bpjs,umum,asuransi,naker,rssm,obat_bebas)
            VALUES (?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('frm_jml'), $p('frm_bpjs'), $p('frm_umum'), $p('frm_asuransi'), $p('frm_naker'), $p('frm_rssm'),
            $p('frm_obat_bebas'),
        ]);

        // 12. Radiologi
        $this->db->query("
            INSERT INTO tc_mod_radiologi (laporan_id,jml_pasien,bpjs,umum,asuransi,naker,xray,usg)
            VALUES (?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('rad_jml'), $p('rad_bpjs'), $p('rad_umum'), $p('rad_asuransi'), $p('rad_naker'),
            $p('rad_xray'), $p('rad_usg'),
        ]);

        // 13-17. Lainnya
        $this->db->query("
            INSERT INTO tc_mod_lainnya (laporan_id,dpjp_visite,
                ambulans_pagi,ambulans_sore,ambulans_malam,
                kendala,kendala_tindak,sarpras,sarpras_tindak,
                kebersihan_tunggu,kebersihan_toilet,kebersihan_lobby,
                keterangan_lain)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ", [
            $id,
            $p('dpjp_visite',''),
            $p('ambulans_pagi',''), $p('ambulans_sore',''), $p('ambulans_malam',''),
            $p('kendala',''), $p('kendala_tindak',''),
            $p('sarpras',''), $p('sarpras_tindak',''),
            $p('kebersihan_tunggu',''), $p('kebersihan_toilet',''), $p('kebersihan_lobby',''),
            $p('keterangan_lain',''),
        ]);
    }

    // ----------------------------------------------------------------
    // GETTERS
    // ----------------------------------------------------------------

    public function get_igd($id) {
        return $this->db->query("SELECT * FROM tc_mod_igd WHERE laporan_id=?", [$id])->row();
    }

    public function get_rawat_jalan($id) {
        return $this->db->query("SELECT * FROM tc_mod_rawat_jalan WHERE laporan_id=?", [$id])->row();
    }

    public function get_hemodialisa($id) {
        return $this->db->query("SELECT * FROM tc_mod_hemodialisa WHERE laporan_id=?", [$id])->row();
    }

    public function get_rawat_inap($id) {
        return $this->db->query("SELECT * FROM tc_mod_rawat_inap WHERE laporan_id=?", [$id])->row();
    }

    public function get_ranap_pengawasan($id) {
        return $this->db->query("SELECT * FROM tc_mod_ranap_pengawasan WHERE laporan_id=? ORDER BY id", [$id])->result();
    }

    public function get_intensive($id) {
        return $this->db->query("SELECT * FROM tc_mod_intensive WHERE laporan_id=?", [$id])->row();
    }

    public function get_intensive_detail($id) {
        return $this->db->query("SELECT * FROM tc_mod_intensive_detail WHERE laporan_id=? ORDER BY unit, id", [$id])->result();
    }

    public function get_vk($id) {
        return $this->db->query("SELECT * FROM tc_mod_vk WHERE laporan_id=?", [$id])->row();
    }

    public function get_vk_detail($id) {
        return $this->db->query("SELECT * FROM tc_mod_vk_detail WHERE laporan_id=? ORDER BY id", [$id])->result();
    }

    public function get_perina($id) {
        return $this->db->query("SELECT * FROM tc_mod_perina WHERE laporan_id=?", [$id])->row();
    }

    public function get_perina_detail($id) {
        return $this->db->query("SELECT * FROM tc_mod_perina_detail WHERE laporan_id=? ORDER BY id", [$id])->result();
    }

    public function get_kamar_operasi($id) {
        return $this->db->query("SELECT * FROM tc_mod_kamar_operasi WHERE laporan_id=? ORDER BY shift, jam", [$id])->result();
    }

    public function get_laboratorium($id) {
        return $this->db->query("SELECT * FROM tc_mod_laboratorium WHERE laporan_id=?", [$id])->row();
    }

    public function get_farmasi($id) {
        return $this->db->query("SELECT * FROM tc_mod_farmasi WHERE laporan_id=?", [$id])->row();
    }

    public function get_radiologi($id) {
        return $this->db->query("SELECT * FROM tc_mod_radiologi WHERE laporan_id=?", [$id])->row();
    }

    public function get_lainnya($id) {
        return $this->db->query("SELECT * FROM tc_mod_lainnya WHERE laporan_id=?", [$id])->row();
    }

    public function verify($id) {
        $user = $this->session->userdata('username') ?: 'system';
        $this->db->query("
            UPDATE tc_mod_laporan SET status='verified', verified_by=?, verified_at=GETDATE()
            WHERE id=? AND status='final'
        ", [$user, $id]);
    }

    public function is_verified($id) {
        $row = $this->db->query("SELECT status FROM tc_mod_laporan WHERE id=?", [$id])->row();
        return $row && $row->status === 'verified';
    }

    public function delete($id) {
        if ($this->is_verified($id)) return false;
        $this->db->query("DELETE FROM tc_mod_laporan WHERE id=?", [$id]);
        return true;
    }

    // ----------------------------------------------------------------
    // FOTO KONDISI LAPANGAN
    // ----------------------------------------------------------------

    public function get_fotos_grouped($id) {
        $rows = $this->db->query(
            "SELECT * FROM tc_mod_foto WHERE laporan_id=? ORDER BY section, urutan, id",
            [$id]
        )->result();
        $grouped = [];
        foreach ($rows as $r) {
            $grouped[$r->section][] = $r;
        }
        return $grouped;
    }

    public function get_foto_by_id($foto_id) {
        return $this->db->query("SELECT * FROM tc_mod_foto WHERE id=?", [$foto_id])->row();
    }

    public function save_foto($laporan_id, $section, $foto_path, $keterangan = '', $urutan = 0) {
        $this->db->query("
            INSERT INTO tc_mod_foto (laporan_id, section, foto_path, keterangan, urutan)
            VALUES (?, ?, ?, ?, ?)
        ", [$laporan_id, $section, $foto_path, $keterangan, $urutan]);
    }

    public function update_foto_keterangan($foto_id, $keterangan) {
        $this->db->query("UPDATE tc_mod_foto SET keterangan=? WHERE id=?", [$keterangan, $foto_id]);
    }

    public function delete_foto_by_id($foto_id) {
        $this->db->query("DELETE FROM tc_mod_foto WHERE id=?", [$foto_id]);
    }
}
