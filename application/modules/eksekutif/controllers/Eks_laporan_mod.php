<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Eks_laporan_mod extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->breadcrumbs->push('Laporan MOD', 'eksekutif/Eks_laporan_mod');
        if ($this->session->userdata('logged') != TRUE) {
            echo 'Session Expired !'; exit;
        }
        $this->load->model('eksekutif/Eks_laporan_mod_model', 'Mod');
        $this->output->enable_profiler(false);
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))
            ? $this->lib_menus->get_menu_by_class(get_class($this))->name
            : 'Laporan MOD';
    }

    public function index() {
        $data = [
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        ];
        $this->load->view('Eks_laporan_mod/index', $data);
    }

    public function get_data() {
        $list = $this->Mod->get_datatables();
        $data = [];
        $no   = $_POST['start'];
        $link = 'eksekutif/Eks_laporan_mod';

        $badge = ['Pagi' => 'warning', 'Sore' => 'info', 'Malam' => 'inverse'];

        foreach ($list as $row) {
            $no++;
            $shift_cls = isset($badge[$row->shift_mod]) ? $badge[$row->shift_mod] : 'default';
            $is_verified = ($row->status === 'verified');
            $status_cls  = $is_verified ? 'primary' : ($row->status == 'final' ? 'success' : 'default');

            // Build action buttons
            $actions = $this->authuser->show_button($link, 'R', $row->id, 2);
            if (!$is_verified) {
                $actions .= $this->authuser->show_button($link, 'U', $row->id, 2);
                $actions .= $this->authuser->show_button($link, 'D', $row->id, 2);
            }
            $actions_print = '<button type="button" class="btn btn-xs btn-info" onclick="loadReportModal('.$row->id.')" title="Cetak Laporan"><i class="ace-icon fa fa-print"></i></button>';
            
            if ($row->status === 'final') {
                $actions_verify = ' <button type="button" class="btn btn-xs btn-purple" onclick="verifyLaporan('.$row->id.')" title="Verifikasi"><i class="ace-icon fa fa-check-circle"></i></button>';
            }else{
                $actions_verify = '';
            }

            $status_label = strtoupper($row->status);
            if ($is_verified) {
                $status_label = '<i class="fa fa-check-circle" style="margin-right:2px"></i>VERIFIED';
            }

            $data[] = [
                '<div class="center">
                    <label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row->id.'"'.($is_verified ? ' disabled' : '').'/>
                        <span class="lbl"></span>
                    </label>
                 </div>',
                '<div class="center">'.$actions.'</div>',
                '<div class="center">'.$actions_print.'</div>',
                '<div class="center">'.$actions_verify.'</div>',
                '<div class="center">'.$no.'</div>',
                date('d/m/Y', strtotime($row->tanggal)),
                htmlspecialchars($row->nama_mod),
                '<div class="center"><span class="badge badge-'.$shift_cls.'">'.$row->shift_mod.'</span></div>',
                '<div class="center"><span class="label label-sm label-'.$status_cls.'">'.$status_label.'</span></div>',
                date('d/m/Y H:i', strtotime($row->created_at)),
            ];
        }

        echo json_encode([
            'draw'            => $_POST['draw'],
            'recordsTotal'    => $this->Mod->count_all(),
            'recordsFiltered' => $this->Mod->count_filtered(),
            'data'            => $data,
        ]);
    }

    public function form($id = null) {
        if ($id) {
            $header = $this->Mod->get_header($id);
            // Jika sudah diverifikasi, paksa read-only
            if ($header && $header->status === 'verified') {
                redirect('eksekutif/Eks_laporan_mod/show/'.$id);
                return;
            }
            $this->breadcrumbs->push('Edit Laporan', 'eksekutif/Eks_laporan_mod/form/'.$id);
            $flag = 'update';
        } else {
            $header = null;
            $this->breadcrumbs->push('Input Laporan', 'eksekutif/Eks_laporan_mod/form');
            $flag = 'create';
        }
        $data = [
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'laporan'     => $header,
            'detail'      => $id ? $this->Mod->get_all_sections($id) : null,
            'id'          => $id,
            'flag'        => $flag,
        ];
        $this->load->view('Eks_laporan_mod/form', $data);
    }

    public function show($id) {
        $this->breadcrumbs->push('Lihat Laporan', 'eksekutif/Eks_laporan_mod/show/'.$id);
        $data = [
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'laporan'     => $this->Mod->get_header($id),
            'detail'      => $this->Mod->get_all_sections($id),
            'id'          => $id,
            'flag'        => 'read',
        ];
        $this->load->view('Eks_laporan_mod/form', $data);
    }

    public function save() {
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            echo json_encode(['status' => 301, 'message' => 'Invalid request']); return;
        }

        $post = $this->input->post(null, true);
        $id   = isset($post['id']) && $post['id'] ? (int)$post['id'] : null;
        $save_status = isset($post['save_status']) && $post['save_status'] === 'draft' ? 'draft' : 'final';

        try {
            // Block update pada laporan yang sudah diverifikasi
            if ($id && $this->Mod->is_verified($id)) {
                echo json_encode(['status' => 301, 'message' => 'Laporan sudah diverifikasi dan tidak dapat diubah.']);
                return;
            }

            if ($id) {
                $this->Mod->update($id, $post, $save_status);
                $msg = $save_status === 'draft' ? 'Draft laporan berhasil disimpan.' : 'Laporan MOD berhasil diperbarui.';
            } else {
                $id = $this->Mod->save($post, $save_status);
                $msg = $save_status === 'draft' ? 'Draft laporan berhasil disimpan.' : 'Laporan MOD berhasil disimpan.';
            }

            $this->_handle_foto_upload($id, $post);

            echo json_encode([
                'status'  => 200,
                'message' => $msg,
                'url'     => 'eksekutif/Eks_laporan_mod/report/' . $id,
                'url_id'  => $id,
            ]);
        } catch (Exception $e) {
            echo json_encode(['status' => 301, 'message' => 'Proses gagal: ' . $e->getMessage()]);
        }
    }

    private function _handle_foto_upload($id, $post) {
        $upload_dir = FCPATH . 'uploaded/mod_laporan/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        // 1. Hapus foto yang ditandai dihapus oleh user
        if (!empty($post['foto_delete'])) {
            foreach ($post['foto_delete'] as $sec => $ids) {
                if (!is_array($ids)) continue;
                foreach ($ids as $foto_id) {
                    $foto_id = (int)$foto_id;
                    if (!$foto_id) continue;
                    $foto = $this->Mod->get_foto_by_id($foto_id);
                    if ($foto && $foto->laporan_id == $id) {
                        @unlink($upload_dir . $foto->foto_path);
                        $this->Mod->delete_foto_by_id($foto_id);
                    }
                }
            }
        }

        // 2. Update keterangan foto yang dipertahankan
        if (!empty($post['foto_ket_exist'])) {
            foreach ($post['foto_ket_exist'] as $sec => $kets) {
                if (!is_array($kets)) continue;
                $keep_ids = isset($post['foto_keep'][$sec]) ? $post['foto_keep'][$sec] : [];
                foreach ($kets as $i => $ket) {
                    if (isset($keep_ids[$i])) {
                        $this->Mod->update_foto_keterangan((int)$keep_ids[$i], $ket);
                    }
                }
            }
        }

        // 3. Upload foto baru
        $sections = [
            'igd', 'rawat_jalan', 'hemodialisa', 'rawat_inap', 'intensive',
            'vk', 'perina', 'kamar_op', 'lab', 'farmasi', 'radiologi',
            'dpjp', 'ambulans', 'kendala', 'sarpras', 'kebersihan', 'keterangan_lain',
        ];

        if (empty($_FILES['foto_file']['name'])) return;

        foreach ($sections as $sec) {
            if (empty($_FILES['foto_file']['name'][$sec])) continue;

            $files = $_FILES['foto_file']['name'][$sec];
            $tmps  = $_FILES['foto_file']['tmp_name'][$sec];
            $errs  = $_FILES['foto_file']['error'][$sec];
            $kets  = isset($post['foto_ket'][$sec]) ? $post['foto_ket'][$sec] : [];

            if (!is_array($files)) continue;

            foreach ($files as $i => $fname) {
                if (empty($fname) || $errs[$i] !== UPLOAD_ERR_OK) continue;
                $ext = strtolower(pathinfo($fname, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed_ext)) continue;
                $new_name = $id . '_' . $sec . '_' . time() . '_' . $i . '.' . $ext;
                if (move_uploaded_file($tmps[$i], $upload_dir . $new_name)) {
                    $ket = isset($kets[$i]) ? trim($kets[$i]) : '';
                    $this->Mod->save_foto($id, $sec, $new_name, $ket, $i);
                }
            }
        }
    }

    public function report_modal($id) {
        $data = [
            'laporan'       => $this->Mod->get_header($id),
            'igd'           => $this->Mod->get_igd($id),
            'rawat_jalan'   => $this->Mod->get_rawat_jalan($id),
            'hemodialisa'   => $this->Mod->get_hemodialisa($id),
            'rawat_inap'    => $this->Mod->get_rawat_inap($id),
            'ranap_detail'  => $this->Mod->get_ranap_pengawasan($id),
            'intensive'     => $this->Mod->get_intensive($id),
            'icu_detail'    => $this->Mod->get_intensive_detail($id),
            'vk'            => $this->Mod->get_vk($id),
            'vk_detail'     => $this->Mod->get_vk_detail($id),
            'perina'        => $this->Mod->get_perina($id),
            'perina_detail' => $this->Mod->get_perina_detail($id),
            'kamar_op'      => $this->Mod->get_kamar_operasi($id),
            'lab'           => $this->Mod->get_laboratorium($id),
            'farmasi'       => $this->Mod->get_farmasi($id),
            'radiologi'     => $this->Mod->get_radiologi($id),
            'lainnya'       => $this->Mod->get_lainnya($id),
            'fotos'         => $this->Mod->get_fotos_grouped($id),
            'id'            => $id,
        ];
        echo $this->load->view('Eks_laporan_mod/report_modal', $data, true);
    }

    public function report($id) {
        $data = [
            'title'         => $this->title,
            'breadcrumbs'   => $this->breadcrumbs->show(),
            'laporan'       => $this->Mod->get_header($id),
            'igd'           => $this->Mod->get_igd($id),
            'rawat_jalan'   => $this->Mod->get_rawat_jalan($id),
            'hemodialisa'   => $this->Mod->get_hemodialisa($id),
            'rawat_inap'    => $this->Mod->get_rawat_inap($id),
            'ranap_detail'  => $this->Mod->get_ranap_pengawasan($id),
            'intensive'     => $this->Mod->get_intensive($id),
            'icu_detail'    => $this->Mod->get_intensive_detail($id),
            'vk'            => $this->Mod->get_vk($id),
            'vk_detail'     => $this->Mod->get_vk_detail($id),
            'perina'        => $this->Mod->get_perina($id),
            'perina_detail' => $this->Mod->get_perina_detail($id),
            'kamar_op'      => $this->Mod->get_kamar_operasi($id),
            'lab'           => $this->Mod->get_laboratorium($id),
            'farmasi'       => $this->Mod->get_farmasi($id),
            'radiologi'     => $this->Mod->get_radiologi($id),
            'lainnya'       => $this->Mod->get_lainnya($id),
            'fotos'         => $this->Mod->get_fotos_grouped($id),
            'id'            => $id,
        ];
        $this->load->view('Eks_laporan_mod/report', $data);
    }

    public function delete() {
        $id = $this->input->post('ID') ? $this->input->post('ID', true) : null;
        $toArray = $id ? explode(',', $id) : [];

        if (!empty($toArray)) {
            $skipped = 0;
            foreach ($toArray as $item) {
                $result = $this->Mod->delete((int)$item);
                if ($result === false) $skipped++;
            }
            if ($skipped > 0 && $skipped == count($toArray)) {
                echo json_encode(['status' => 301, 'message' => 'Laporan yang sudah diverifikasi tidak dapat dihapus.']);
            } elseif ($skipped > 0) {
                echo json_encode(['status' => 200, 'message' => 'Berhasil dihapus, '.$skipped.' laporan terverifikasi dilewati.']);
            } else {
                echo json_encode(['status' => 200, 'message' => 'Laporan MOD berhasil dihapus.']);
            }
        } else {
            echo json_encode(['status' => 301, 'message' => 'Tidak ada item yang dipilih.']);
        }
    }

    public function verify() {
        $id = $this->input->post('id', true);
        if (!$id) {
            echo json_encode(['status' => 301, 'message' => 'ID tidak valid.']);
            return;
        }

        $header = $this->Mod->get_header((int)$id);
        if (!$header) {
            echo json_encode(['status' => 301, 'message' => 'Laporan tidak ditemukan.']);
            return;
        }
        if ($header->status !== 'final') {
            echo json_encode(['status' => 301, 'message' => 'Hanya laporan berstatus Final yang dapat diverifikasi.']);
            return;
        }

        $this->Mod->verify((int)$id);
        echo json_encode(['status' => 200, 'message' => 'Laporan berhasil diverifikasi.']);
    }

    public function search_karyawan() {
        $keyword = $this->input->post('keyword', true);
        if (!$keyword || strlen($keyword) < 2) {
            echo json_encode([]);
            return;
        }
        $result = $this->db->like('nama_pegawai', $keyword)
                           ->order_by('nama_pegawai', 'ASC')
                           ->limit(15)
                           ->get('mt_karyawan')->result();
        $arr = [];
        foreach ($result as $r) {
            $arr[] = $r->nama_pegawai;
        }
        echo json_encode($arr);
    }
}
