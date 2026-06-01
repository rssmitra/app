<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Input_dt_so extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->breadcrumbs->push('Index', 'inventory/so/Input_dt_so');
        if ($this->session->userdata('logged') != TRUE) {
            echo 'Session Expired !'; exit;
        }
        $this->load->model('inventory/so/Input_dt_so_model', 'Input_dt_so');
        $this->output->enable_profiler(false);
        $this->title        = ($this->lib_menus->get_menu_by_class(get_class($this)))
                              ? $this->lib_menus->get_menu_by_class(get_class($this))->name
                              : 'Title';
        $this->agenda_so_id = ($this->session->userdata('session_input_so')['agenda_so_id'])
                              ? $this->session->userdata('session_input_so')['agenda_so_id']
                              : '';
    }

    // ── Index ────────────────────────────────────────────────────────────────

    public function index() {
        $data = array(
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        if ($this->session->userdata('session_input_so')) {
            $bag = $this->session->userdata('session_input_so')['bagian'];
            echo '<script>getMenu(\'' . base_url() . 'inventory/so/Input_dt_so/form_input_dt_so/' . $bag . '\')</script>';
        }
        $this->load->view('so/Input_dt_so/index', $data);
    }

    public function form_input_dt_so($kode_bag = '') {
        $this->breadcrumbs->push('Input ' . strtolower($this->title), 'Input_dt_so/form_input_dt_so/' . $kode_bag);
        $data['value']       = $this->Input_dt_so->get_agenda_by_id($this->agenda_so_id);
        $data['title']       = $this->title;
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $this->load->view('so/Input_dt_so/form', $data);
    }

    // ── DataTable data ───────────────────────────────────────────────────────

    public function get_data() {
        $bag = isset($_GET['bag']) ? $_GET['bag'] : '';

        if ($bag == '070101') {
            $list            = $this->Input_dt_so->get_datatables_nm();
            $recordsTotal    = $this->Input_dt_so->count_all_nm();
            $recordsFiltered = $this->Input_dt_so->count_filtered_nm();
        } else {
            $list            = $this->Input_dt_so->get_datatables();
            $recordsTotal    = $this->Input_dt_so->count_all();
            $recordsFiltered = $this->Input_dt_so->count_filtered();
        }

        $data = array();
        $no   = (int)$_POST['start'];

        foreach ($list as $r) {
            $no++;
            $row = array();

            $row_id    = $r->kode_brg . '_' . $r->kode_brg . '_' . $this->agenda_so_id;
            $kode_brg  = $r->kode_brg;
            $kode_bag  = $r->kode_bagian;
            $agenda_id = $this->agenda_so_id;
            $status_so = $r->status_so; // null | 'draft' | 'final'
            $locked    = ($status_so === 'final') ? 'readonly style="background:#f0f0f0;width:75px;text-align:center;font-weight:bold"' : 'style="width:75px;text-align:center;font-weight:bold"';

            // ── 0: NO ────────────────────────────────────────────────────────
            $row[] = '<div class="center">' . $no . '</div>';

            // ── 1: NAMA BARANG ───────────────────────────────────────────────
            $row[] = '<b>' . htmlspecialchars($kode_brg) . '</b><br>' . ucwords($r->nama_brg);

            // ── 2: SATUAN ────────────────────────────────────────────────────
            $row[] = '<div class="center">' . $r->satuan_kecil . '</div>';

            // ── 3: CUT OFF STOK ──────────────────────────────────────────────
            // cutoff_stok = TOP 1 stok_akhir tc_kartu_stok <= tgl cutoff (dari $_GET['cutoff'])
            $cutoff = ($r->cutoff_stok !== null) ? (int)$r->cutoff_stok : (int)$r->jml_sat_kcl;
            $row[] = '<div class="center" style="color:#1a5276;font-size:13px;font-weight:bold">'
                   . number_format($cutoff) . '</div>';

            // ── 4: STOK AKHIR BERJALAN ────────────────────────────────────────
            // Live stock used for JS calculations; display uses the DB snapshot when final.
            $stok_akhir = (int)$r->jml_sat_kcl;
            if ($status_so === 'final' && $r->stok_akhir_berjalan !== null) {
                $display_stok_akhir = (int)$r->stok_akhir_berjalan;
            } else {
                $display_stok_akhir = $stok_akhir;
            }
            $row[] = '<div class="center" style="color:#145a32;font-size:13px;font-weight:bold">'
                   . number_format($display_stok_akhir) . '</div>';

            // ── 5: PERGERAKAN STOK ────────────────────────────────────────────
            $mv_in  = (int)$r->total_pemasukan;
            $mv_out = (int)$r->total_pengeluaran;
            $mv_ttl = $mv_in - $mv_out;
            
            if ($mv_in > 0 || $mv_out > 0) {
                $row[] = '<div class="center" style="font-size:12px">'
                       . '<span class="text-success">+' . number_format($mv_in)  . '</span>&nbsp; '
                       . '<span class="text-danger">&minus;' . number_format($mv_out) . '</span>'
                       . '</div>';
            } else {
                $row[] = '<div class="center" style="color:#aaa;font-size:12px">&mdash;</div>';
            }

            // ── 6: STOK OPNAME (input) ────────────────────────────────────────
            $so_val    = ($r->stok_sekarang !== null) ? (int)$r->stok_sekarang : '';
            $on_change = ($status_so !== 'final')
                ? 'onchange="calcSelisih(\'' . $kode_brg . '\',' . $agenda_id . ',' . $stok_akhir . ')"'
                : '';
            if ($status_so === 'final') {
                $input_so = '<input type="text" id="row_so_' . $row_id . '" readonly'
                          . ' style="width:75px;text-align:center;background:#f0f0f0;font-weight:bold"'
                          . ' value="' . $so_val . '">';
            } else {
                $input_so = '<input type="text" id="row_so_' . $row_id . '"'
                          . ' style="width:75px;text-align:center;background:#fef9e7;font-weight:bold"'
                          . ' value="' . $so_val . '" ' . $on_change . '>';
            }
            $row[] = '<div class="center">' . $input_so . '</div>';

            // ── 7: STOK EXPIRED (input) ───────────────────────────────────────
            $exp_val = ($r->stok_exp !== null) ? (int)$r->stok_exp : 0;
            if ($status_so === 'final') {
                $input_exp = '<input type="text" id="row_exp_' . $row_id . '" readonly'
                           . ' style="width:75px;text-align:center;background:#f0f0f0;font-weight:bold"'
                           . ' value="' . $exp_val . '">';
            } else {
                $input_exp = '<input type="text" id="row_exp_' . $row_id . '"'
                           . ' style="width:75px;text-align:center;background:#fdedec;font-weight:bold"'
                           . ' value="' . $exp_val . '"'
                           . ' onchange="calcSelisih(\'' . $kode_brg . '\',' . $agenda_id . ',' . $stok_akhir . ')">';
            }
            $row[] = '<div class="center">' . $input_exp . '</div>';

            // ── 8: SELISIH ────────────────────────────────────────────────────
            // When final: read from DB. Otherwise: compute live from current inputs.
            $adj_val = ($r->stok_adjustment !== null) ? (int)$r->stok_adjustment : 0;
            if ($status_so === 'final' && $r->selisih !== null) {
                $selisih_val  = (int)$r->selisih;
                $s_color      = ($selisih_val > 0) ? '#27ae60' : (($selisih_val < 0) ? '#c0392b' : '#555');
                $s_prefix     = ($selisih_val >= 0) ? '+' : '';
                $selisih_html = '<span style="color:' . $s_color . ';font-weight:bold">'
                              . $s_prefix . number_format($selisih_val) . '</span>';
            } elseif ($so_val !== '') {
                $selisih_val  = ((int)$so_val + $exp_val + $adj_val) - $stok_akhir;
                $s_color      = ($selisih_val > 0) ? '#27ae60' : (($selisih_val < 0) ? '#c0392b' : '#555');
                $s_prefix     = ($selisih_val >= 0) ? '+' : '';
                $selisih_html = '<span style="color:' . $s_color . ';font-weight:bold">'
                              . $s_prefix . number_format($selisih_val) . '</span>';
            } else {
                $selisih_html = '<span style="color:#aaa">&mdash;</span>';
            }
            $row[] = '<div class="center" id="row_selisih_' . $row_id . '">' . $selisih_html . '</div>';

            // ── 9: ADJUSTMENT (input) ─────────────────────────────────────────
            if ($status_so === 'final') {
                $input_adj = '<input type="text" id="row_adj_' . $row_id . '" readonly'
                           . ' style="width:75px;text-align:center;background:#f0f0f0;font-weight:bold"'
                           . ' value="' . $adj_val . '">';
            } else {
                $input_adj = '<input type="text" id="row_adj_' . $row_id . '"'
                           . ' style="width:75px;text-align:center;background:#eaf4fb;font-weight:bold"'
                           . ' value="' . $adj_val . '"'
                           . ' onchange="calcSelisih(\'' . $kode_brg . '\',' . $agenda_id . ',' . $stok_akhir . ')">';
            }
            $row[] = '<div class="center">' . $input_adj . '</div>';

            // ── 10: STOK FINAL (calculated) ────────────────────────────────────
            // Stok Final = Stok Opname + Adjustment
            if ($so_val !== '') {
                $sf_val          = (int)$so_val + $adj_val;
                $sf_color        = ($sf_val > 0) ? '#1a5276' : (($sf_val < 0) ? '#c0392b' : '#555');
                $stok_final_html = '<span style="color:' . $sf_color . ';font-weight:bold">'
                                 . number_format($sf_val) . '</span>';
            } else {
                $stok_final_html = '<span style="color:#aaa">&mdash;</span>';
            }
            $row[] = '<div class="center" id="row_stokfinal_' . $row_id . '">' . $stok_final_html . '</div>';

            // ── 11: STATUS AKTIF (toggle) ──────────────────────────────────────
            $is_active = (int)$r->status_aktif;
            $chk_aktif = ($is_active != 0) ? 'checked' : '';
            $disabled  = ($status_so === 'final') ? 'disabled' : '';
            $row[] = '<div class="center">'
                   . '<label>'
                   . '<input name="status_brg_aktif" id="stat_on_off_' . $row_id . '"'
                   . ' onclick="setStatusAktifBrg(\'' . $kode_brg . '\',\'' . $kode_bag . '\',' . $agenda_id . ',' . $stok_akhir . ',' . $cutoff . ',' . $mv_in . ',' . $mv_out . ')"'
                   . ' class="ace ace-switch ace-switch-3" type="checkbox"'
                   . ' ' . $chk_aktif . ' value="' . $is_active . '" ' . $disabled . '>'
                   . '<span class="lbl"></span>'
                   . '</label>'
                   . '</div>';

            // ── 12: KLARIFIKASI (input) ────────────────────────────────────────
            $klar_val = htmlspecialchars(isset($r->klarifikasi_stok) ? $r->klarifikasi_stok : '');
            if ($status_so === 'final') {
                $input_klar = '<input type="text" id="row_klar_' . $row_id . '" readonly'
                            . ' style="width:100%;font-size:12px;background:#f0f0f0"'
                            . ' value="' . $klar_val . '">';
            } else {
                $input_klar = '<input type="text" id="row_klar_' . $row_id . '"'
                            . ' style="width:100%;font-size:12px" placeholder="Klarifikasi selisih..."'
                            . ' value="' . $klar_val . '">';
            }
            $row[] = '<div>' . $input_klar . '</div>';

            // ── 13: STATUS + SIMPAN (merged) ───────────────────────────────────
            if ($status_so === 'final') {
                $badge = '<span class="label label-success"><i class="fa fa-lock"></i> Final</span>';
            } elseif ($status_so === 'draft') {
                $badge = '<span class="label label-warning"><i class="fa fa-edit"></i> Draft</span>';
            } else {
                $badge = '<span class="label label-default">Belum Input</span>';
            }
            $petugas_info = $r->nama_petugas
                ? '<br><small style="color:#777">' . $r->nama_petugas . '</small>'
                  . '<br><small style="color:#aaa">' . $this->tanggal->formatDateTime($r->tgl_stok_opname) . '</small>'
                : '';

            $nama_js   = htmlspecialchars(json_encode((string)$r->nama_brg),    ENT_QUOTES, 'UTF-8');
            $satuan_js = htmlspecialchars(json_encode((string)$r->satuan_kecil), ENT_QUOTES, 'UTF-8');
            $extra     = $stok_akhir . ',' . $cutoff . ',' . $mv_in . ',' . $mv_out . ',' . $nama_js . ',' . $satuan_js;

            if ($status_so === 'final') {
                $btn = '<div style="margin-top:5px;color:#27ae60;font-size:11px">'
                     . '<i class="fa fa-lock"></i> Finalized</div>';
            } elseif ($status_so === 'draft') {
                $btn = '<div style="margin-top:5px">'
                     . '<button class="btn btn-xs btn-success" style="white-space:nowrap"'
                     . ' onclick="saveFinalRow(\'' . $kode_brg . '\',\'' . $kode_bag . '\',' . $agenda_id . ',' . $extra . ')">'
                     . '<i class="fa fa-check-circle"></i> Save Final</button>'
                     . '</div>';
            } else {
                $btn = '<div style="margin-top:5px">'
                     . '<button class="btn btn-xs btn-info" style="white-space:nowrap"'
                     . ' onclick="saveDraftRow(\'' . $kode_brg . '\',\'' . $kode_bag . '\',' . $agenda_id . ',' . $extra . ')">'
                     . '<i class="fa fa-save"></i> Save Draft</button>'
                     . '</div>';
            }
            $row[] = '<div class="center">' . $badge . $petugas_info . $btn . '</div>';

            $data[] = $row;
        }

        echo json_encode(array(
            'draw'            => $_POST['draw'],
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data,
        ));
    }

    // ── Session setup ────────────────────────────────────────────────────────

    public function process() {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('agenda_so_id',  'Agenda SO',   'trim|required');
        $val->set_rules('tanggal_input', 'Tanggal',     'trim|required');
        $val->set_rules('waktu_input',   'Waktu/Jam',   'trim|required');
        $val->set_rules('bagian',        'Bagian/Unit', 'trim|required');
        $val->set_rules('kode_petugas',  'Petugas',     'trim|xss_clean');
        $val->set_message('required', 'Silahkan isi field "%s"');
        $val->set_message('integer',  'Field "%s" harus diisi dengan angka');

        if ($val->run() == FALSE) {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        } else {
            $this->db->trans_begin();
            $dt_bag = $this->db->select('nama_bagian') ->get_where('mt_bagian',   array('kode_bagian' => $val->set_value('bagian')))->row();
            $nm_peg = $this->db->select('nama_pegawai')->get_where('mt_karyawan', array('no_induk'    => $val->set_value('kode_petugas')))->row();
            $dataexc = array(
                'agenda_so_id'     => $this->regex->_genRegex($val->set_value('agenda_so_id'),  'RGXQSL'),
                'tanggal_input'    => $this->regex->_genRegex($val->set_value('tanggal_input'), 'RGXQSL'),
                'waktu_input'      => $this->regex->_genRegex($val->set_value('waktu_input'),   'RGXQSL'),
                'bagian'           => $this->regex->_genRegex($val->set_value('bagian'),        'RGXQSL'),
                'nama_bagian'      => $dt_bag->nama_bagian,
                'no_induk_pegawai' => $this->regex->_genRegex($val->set_value('kode_petugas'), 'RGXQSL'),
                'nama_pegawai'     => $nm_peg->nama_pegawai,
            );
            $this->session->set_userdata('session_input_so', $dataexc);
            echo json_encode(array(
                'status'        => 200,
                'message'       => 'Proses Berhasil Dilakukan',
                'redirect_page' => 'inventory/so/Input_dt_so/form_input_dt_so/' . $dataexc['bagian'],
            ));
        }
    }

    public function destroy_session_input_so() {
        $this->session->unset_userdata('session_input_so');
        echo json_encode(array('status' => 200, 'message' => 'Silahkan masukan data petugas SO kembali'));
    }

    // ── Save Draft ───────────────────────────────────────────────────────────

    public function save_draft_so() {
        $kode_bagian = $this->input->post('kode_bagian');
        if (!$kode_bagian) {
            echo json_encode(array('status' => 301, 'message' => 'Kode bagian tidak valid'));
            return;
        }
        if ($kode_bagian == '070101') {
            $this->Input_dt_so->save_draft_nm();
        } else {
            $this->Input_dt_so->save_draft_medis();
        }
        echo json_encode(array('status' => 200, 'message' => 'Data berhasil disimpan sebagai Draft'));
    }

    // ── Save Final ───────────────────────────────────────────────────────────

    public function save_final_so() {
        $kode_bagian = $this->input->post('kode_bagian');
        $stok_opname = $this->input->post('stok_opname');

        if (!$kode_bagian) {
            echo json_encode(array('status' => 301, 'message' => 'Kode bagian tidak valid'));
            return;
        }
        if ($stok_opname === '' || $stok_opname === null) {
            echo json_encode(array('status' => 301, 'message' => 'Stok Opname harus diisi sebelum finalisasi'));
            return;
        }

        $this->db->trans_begin();

        if ($kode_bagian == '070101') {
            $result = $this->Input_dt_so->save_final_nm();
        } else {
            $result = $this->Input_dt_so->save_final_medis();
        }

        if ($this->db->trans_status() === FALSE || $result === false) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Proses finalisasi gagal. Data mungkin sudah final atau terjadi error.'));
        } else {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Data berhasil difinalisasi dan stok telah dimutasi'));
        }
    }

    // ── Status Aktif toggle (existing) ──────────────────────────────────────

    public function set_status_brg() {
        $value = ($_POST['value'] == 0) ? 1 : 0;

        if ($_POST['kode_bagian'] == '070101') {
            $this->Input_dt_so->update_status_brg('mt_barang_nm',      array('is_active' => $value),        array('kode_brg' => $_POST['kode_brg']));
            $this->db->trans_commit();
            $this->Input_dt_so->update_status_brg('tc_stok_opname_nm', array('set_status_aktif' => $value), array('kode_brg' => $_POST['kode_brg'], 'agenda_so_id' => $_POST['agenda_so_id'], 'kode_bagian' => $_POST['kode_bagian']));
            $this->db->trans_commit();
            $this->Input_dt_so->update_status_brg('mt_depo_stok_nm',   array('is_active' => $value),        array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']));
            $this->db->trans_commit();
        } else {
            if ($_POST['kode_bagian'] == '060201') {
                $this->Input_dt_so->update_status_brg('mt_barang', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg']));
            }
            $this->Input_dt_so->update_status_brg('mt_depo_stok',   array('is_active' => $value),        array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']));
            $this->Input_dt_so->update_status_brg('tc_stok_opname', array('set_status_aktif' => $value), array('kode_brg' => $_POST['kode_brg'], 'agenda_so_id' => $_POST['agenda_so_id'], 'kode_bagian' => $_POST['kode_bagian']));
        }

        echo json_encode(array('status' => 200, 'message' => 'Status barang berhasil diperbarui'));
    }

    // ── Delete row (existing) ────────────────────────────────────────────────

    public function delete_row() {
        if ($_POST['kode_bagian'] == '070101') {
            $this->db->where('kode_depo_stok', $_POST['ID'])->delete('mt_depo_stok_nm');
        } else {
            $this->db->where('kode_depo_stok', $_POST['ID'])->delete('mt_depo_stok');
        }
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

}

/* End of file Input_dt_so.php */
/* Location: ./application/modules/inventory/controllers/so/Input_dt_so.php */
