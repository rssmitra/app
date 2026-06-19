<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengiriman_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Pengiriman_unit');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan/Penerimaan_brg_model', 'Penerimaan_brg');
        $this->load->model('purchasing/pendistribusian/Pengiriman_unit_model', 'Pengiriman_unit');
        $this->load->model('purchasing/pendistribusian/Permintaan_stok_unit_model', 'Permintaan_stok_unit');
        // load libraries
        $this->load->library('stok_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function form($id='')
    {
        $flag = isset($_GET['flag'])?$_GET['flag']:'medis';
        $data = [];
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['id'] = $id; 
            
            $data['value'] = $this->Permintaan_stok_unit->get_by_id($id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        $data['type'] = $flag; 
        // echo "<pre>";print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form', $data);
    }

    public function form_edit_brg($id_tc_permintaan_inst_det='')
    {
        $data = [];
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        $kode_gudang = ($_GET['flag']=='non_medis')?'070101':'060201';
        $data['kode_gudang'] = $kode_gudang;
        $data['value'] = $this->Pengiriman_unit->get_permintaan_inst_det_by_id($id_tc_permintaan_inst_det, $_GET['flag']);
        // echo '<pre>';print_r($data);die;
        
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_edit_brg', $data);
    }

    public function form_retur()
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pengiriman_unit/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = 'Retur Barang ke Gudang';
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        $data['form'] = 'retur';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_retur', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Permintaan_stok_unit->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permintaan_inst.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permintaan_inst;
            if( $row_list->status_acc != 1 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','R',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','U',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','D',$row_list->id_tc_permintaan_inst,6).'</li>
                                </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/pendistribusian/Permintaan_stok_unit/print_preview/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            $row[] = '<div class="center">'.$row_list->id_tc_permintaan_inst.'</div>';
            // $row[] = $row_list->nomor_permintaan;
            
            // Determine label based on flag parameter
            $flag = isset($_GET['flag']) ? $_GET['flag'] : '';
            if ($flag == 'medis') {
                $label_flag = '<span style="color: green; font-weight: bold">Medis</span>';
            } elseif ($flag == 'non_medis') {
                $label_flag = '<span style="color: blue; font-weight: bold">Non Medis</span>';
            } else {
                $label_flag = '';
            }

            $jenis_permintaan = ($row_list->jenis_permintaan==0)?'Rutin':'Cito';
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_permintaan).'<br>'.$label_flag.' - '.ucfirst($jenis_permintaan).'';
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->nama_user_input).'</div>';
            $row[] = '<div class="left">'.$row_list->catatan.'</div>';
            $tgl_acc = ($row_list->tgl_acc ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $this->tanggal->formatDateDmy($row_list->tgl_acc);
            $acc_by = ($row_list->acc_by ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $row_list->acc_by;
            $row[] = '<div class="center">'.$tgl_acc.'</div>';
            $row[] = '<div class="center">'.$acc_by.'</div>';
            if($row_list->tgl_acc == null)
            {
                $style_status = '<span style="width: 100% !important" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Belum diverifikasi</span>';
            }else{
                $style_status = ($row_list->status_acc == 1) ? '<span class="label label-success" style="width: 100% !important"><i class="fa fa-check"></i> Disetujui</span>' :'<span style="width: 100% !important" class="label label-danger"><i class="fa fa-times"></i> Tidak disetujui</span>';
            }
            
            $row[] = '<div class="center">'.$style_status.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_pengiriman).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_input_terima).'</div>';
            $row[] = '<div class="center">'.ucfirst($row_list->yg_terima).'</div>';
            $btn_kirim_permintaan = ($row_list->status_acc == null) ? '<a href="#" onclick="kirim_permintaan('."'".$row_list->id_tc_permintaan_inst."'".')" title="Kirim Permintaan" class="label label-xs label-primary"><i class="fa fa-paper-plane"></i> Kirim</a>' : '';
            if($row_list->send_to_verify == 1){
                $btn_kirim_permintaan = '<i class="fa fa-check bigger-150 green" title="Terkirim"></i>';
            }
            $row[] = '<div class="center">'.$btn_kirim_permintaan.'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Permintaan_stok_unit->count_all(),
                        "recordsFiltered" => $this->Permintaan_stok_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form_pengiriman_unit()
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Pengimriman Barang ', 'Pengiriman_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'?ID='.$_GET['ID'].'&flag='.$_GET['flag']);

        /*define data variabel*/
        $result = $this->Penerimaan_brg->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        $data['id'] = $_GET['ID'];
        $data['nomor_permintaan'] = $this->master->format_nomor_permintaan($_GET['flag']);
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['penerimaan'] = $result;
        $data['value'] = $this->Pengiriman_unit->get_by_id($_GET['ID']);
        $data['title'] = 'Pengiriman Barang ke Unit/Depo';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_pengiriman_unit', $data);
    }

    public function process_edit_brg()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_brg_revisi', 'Kode Barang', 'trim|required');
        $val->set_rules('qtyBrg', 'Jumlah Revisi Barang', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst_det':'tc_permintaan_inst_nm_det';
            $is_bhp = isset($_POST['is_bhp']) ? 1 : 0 ;
            $dataexc = array(
                'rev_kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg_revisi'),'RGXQSL'),
                'rev_qty' => $this->regex->_genRegex($_POST['qtyBrg'],'RGXINT'),
                'is_bhp' => $this->regex->_genRegex($is_bhp,'RGXINT'),
            );
            $this->Pengiriman_unit->update($table, ['id_tc_permintaan_inst_det' => $_POST['id_det']], $dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function process_pengiriman_brg_unit()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('tgl_distribusi', 'Tanggal Distribusi', 'trim');
        $val->set_rules('catatan', 'Catatan', 'trim');
        $val->set_rules('yang_menyerahkan', 'Yang Menyerahkan', 'trim|required');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            
            $table = ($_POST['flag_cart']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            $mt_depo_stok = ($_POST['flag_cart']=='medis')?'mt_depo_stok':'mt_depo_stok_nm';
            $kode_gudang = ($_POST['flag_cart']=='medis')?'060201':'070101';
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $_POST['kode_bagian_minta']));

            $dataexc = array(
                'tgl_pengiriman' => $val->set_value('tgl_distribusi').' '.date(' H:i:s '),
                'keterangan_kirim' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                'yg_serah' => $this->regex->_genRegex($val->set_value('yang_menyerahkan'),'RGXQSL'),
                'updated_date' => date('Y-m-d H:i:s'),
                'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
            );
            // echo "<pre>";print_r($dataexc);die;
            $newId = $this->Pengiriman_unit->update($table, ['id_tc_permintaan_inst' => $_POST['id']], $dataexc);

            $cart_data     = $this->Pengiriman_unit->get_cart_data_by_id($_POST['flag_cart'], $_POST['selected_id']);
            $qty_kirim_map = isset($_POST['qty_kirim']) && is_array($_POST['qty_kirim']) ? $_POST['qty_kirim'] : [];

            // ── Validasi: cek stok gudang tidak kosong sebelum proses ─────────────
            $mt_rekap          = ($table === 'tc_permintaan_inst_nm') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok';
            $stok_kosong_items = [];
            foreach ($cart_data as $row_brg) {
                $eff_kode    = (!empty($row_brg->rev_kode_brg)) ? $row_brg->rev_kode_brg : $row_brg->kode_brg;
                $eff_nama    = (!empty($row_brg->brg_revisi))   ? $row_brg->brg_revisi   : $row_brg->nama_brg;
                $kode_gd_esc = $this->db->escape_str($kode_gudang);
                $kode_br_esc = $this->db->escape_str($eff_kode);
                $stok_r      = $this->db->query(
                    "SELECT jml_sat_kcl FROM $mt_rekap WHERE kode_brg='$kode_br_esc' AND kode_bagian_gudang='$kode_gd_esc'"
                )->row();
                $stok_val = ($stok_r && !empty($stok_r->jml_sat_kcl)) ? intval($stok_r->jml_sat_kcl) : 0;
                if ($stok_val <= 0) {
                    $stok_kosong_items[] = htmlspecialchars($eff_nama, ENT_QUOTES);
                }
            }
            if (!empty($stok_kosong_items)) {
                $this->db->trans_rollback();
                echo json_encode([
                    'status'  => 301,
                    'message' => 'Stok gudang kosong! Barang berikut tidak dapat didistribusikan:<br><b>'
                                 . implode(', ', $stok_kosong_items) . '</b>',
                ]);
                return;
            }
            // ─────────────────────────────────────────────────────────────────────

            // update stok gudang
            foreach( $cart_data as $row_brg ){
                // kode & qty: gunakan revisi jika ada
                $kode_brg = (!empty($row_brg->rev_kode_brg)) ? $row_brg->rev_kode_brg : $row_brg->kode_brg;
                $qty_auto = (!empty($row_brg->rev_qty))      ? $row_brg->rev_qty      : $row_brg->qty;

                // qty kirim: ambil dari input form jika ada, fallback ke qty_auto
                $id_det  = $row_brg->id_tc_permintaan_inst_det;
                $qty_brg = (isset($qty_kirim_map[$id_det]) && intval($qty_kirim_map[$id_det]) > 0)
                         ? intval($qty_kirim_map[$id_det])
                         : $qty_auto;

                // batasi agar tidak melebihi jumlah yg disetujui
                if ($qty_brg > intval($row_brg->jml_acc_atasan)) {
                    $qty_brg = intval($row_brg->jml_acc_atasan);
                }

                $update_detail = array(
                    'tgl_kirim'     => date('Y-m-d H:i:s'),
                    'jumlah_kirim'  => $qty_brg,
                    'petugas_kirim' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                );
                $this->Pengiriman_unit->update($table.'_det', ['id_tc_permintaan_inst_det' => $id_det], $update_detail);
                $this->stok_barang->stock_process($kode_brg, $qty_brg, $kode_gudang, 3, ' '.$nama_bagian.' &nbsp; ['.$_POST['id'].'] ', 'reduce');
                $this->db->trans_commit();

            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag_cart'], 'id' => $newId));
            }
        }
    }

    public function get_detail_cart()
    {
        /*get data from model*/
        $list = $this->Pengiriman_unit->get_cart_data();

        $data      = array();
        $arr_count = array();
        $no        = 0;

        // ── Batch queries: distribusi terakhir & transaksi pelayanan ──────────
        $dist_map  = [];
        $trans_map = [];

        if (!empty($list)) {
            $kode_brgs   = array_unique(array_map(function($r){ return $r->kode_brg; }, $list));
            $kode_bagian = $list[0]->kode_bagian; // kode_bagian_minta
            $flag        = $this->input->get('flag') ?: 'medis';
            $tbl         = ($flag === 'non_medis') ? 'tc_permintaan_inst_nm'     : 'tc_permintaan_inst';
            $tbl_det     = ($flag === 'non_medis') ? 'tc_permintaan_inst_nm_det' : 'tc_permintaan_inst_det';

            $brgs_esc = array_map([$this->db, 'escape_str'], $kode_brgs);
            $brgs_in  = "'".implode("','", $brgs_esc)."'";
            $bag_esc  = $this->db->escape_str($kode_bagian);

            // 1. Distribusi terakhir yang sudah dikirim per kode_brg ke unit ini
            $sql_dist = "
                SELECT kode_brg, tgl_pengiriman, jumlah_penerimaan as jumlah_kirim
                FROM (
                    SELECT b.kode_brg, a.tgl_pengiriman, b.jumlah_penerimaan,
                           ROW_NUMBER() OVER (PARTITION BY b.kode_brg ORDER BY a.tgl_pengiriman DESC) AS rn
                    FROM $tbl a
                    INNER JOIN $tbl_det b ON b.id_tc_permintaan_inst = a.id_tc_permintaan_inst
                    WHERE a.kode_bagian_minta = '$bag_esc'
                      AND b.kode_brg IN ($brgs_in)
                      AND (a.status_batal IS NULL OR a.status_batal != 1)
                      AND a.tgl_pengiriman IS NOT NULL
                      AND b.jumlah_penerimaan > 0
                ) t WHERE rn = 1
            ";
            foreach ($this->db->query($sql_dist)->result() as $r) {
                $dist_map[$r->kode_brg] = $r;
            }

            // 2. Jumlah pasien & mutasi sejak distribusi terakhir s/d hari ini
            //    Date range per item: tgl_pengiriman terakhir (dari dist_map) → GETDATE()
            //    JOIN subquery distribusi agar filter tanggal bersifat dinamis per kode_brg
            $sql_trans = "
                SELECT t.kode_barang,
                       COUNT(DISTINCT t.no_kunjungan)       AS jml_pasien,
                       SUM(CAST(t.jumlah AS FLOAT))         AS jml_mutasi,
                       CONVERT(VARCHAR(10), dist.tgl_dist_terakhir, 103) AS tgl_mulai
                FROM tc_trans_pelayanan t
                INNER JOIN (
                    SELECT b.kode_brg,
                           MAX(a.tgl_pengiriman) AS tgl_dist_terakhir
                    FROM {$tbl} a
                    INNER JOIN {$tbl_det} b ON b.id_tc_permintaan_inst = a.id_tc_permintaan_inst
                    WHERE a.kode_bagian_minta = '$bag_esc'
                      AND b.kode_brg IN ($brgs_in)
                      AND (a.status_batal IS NULL OR a.status_batal != 1)
                      AND a.tgl_pengiriman IS NOT NULL
                      AND b.jumlah_kirim > 0
                    GROUP BY b.kode_brg
                ) dist ON dist.kode_brg = t.kode_barang
                       AND t.tgl_transaksi >= dist.tgl_dist_terakhir
                WHERE t.kode_barang IN ($brgs_in)
                  AND t.kode_bagian  = '$bag_esc'
                  AND t.tgl_transaksi <= GETDATE()
                GROUP BY t.kode_barang, dist.tgl_dist_terakhir
            ";
            // echo "<pre>";print_r($sql_trans);die;
            foreach ($this->db->query($sql_trans)->result() as $r) {
                $trans_map[$r->kode_barang] = $r;
            }
        }
        // ─────────────────────────────────────────────────────────────────────

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            
            // Stok gudang aktual (pakai stok revisi jika ada)
            $stok_aktual = (!empty($row_list->stok_gdg_revisi))
                ? intval($row_list->stok_gdg_revisi)
                : intval($row_list->jumlah_stok_gudang);

            $is_pending  = false;
            $stok_kosong = false;

            if($row_list->status_verif == 1){
                if($row_list->jumlah_kirim == $row_list->jml_acc_atasan){
                    // Sudah terkirim semua
                    $row[] = '<div class="center"><i class="fa fa-check" style="color:#15803d"></i></div>';
                } elseif($stok_aktual <= 0) {
                    // Stok gudang kosong — tidak bisa distribusi
                    $row[] = '<div class="center" title="Stok gudang kosong, tidak dapat didistribusikan">'
                           . '<i class="fa fa-ban" style="color:#c0392b;font-size:14px"></i>'
                           . '</div>';
                    $stok_kosong = true;
                } else {
                    // Pending, stok tersedia — tampilkan checkbox
                    $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]"
                                   value="'.$row_list->id_tc_permintaan_inst_det.'"
                                   data-nama="'.htmlspecialchars($row_list->nama_brg, ENT_QUOTES).'"
                                   data-stok="'.$stok_aktual.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
                    $arr_count[] = 1;
                    $is_pending  = true;
                }
            } else {
                $row[] = '<div class="center"><i class="fa fa-times" style="color:#c0392b"></i></div>';
            }

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="left" style="font-size:11px">'.$row_list->kode_brg.'</div>';

            $nama_brg = ($row_list->brg_revisi == null)
                ? $row_list->nama_brg
                : '<s style="color:#c0392b">'.$row_list->nama_brg.'</s> <i class="fa fa-arrow-right" style="color:#888"></i> <b>'.$row_list->brg_revisi.'</b>';
            $qty = ($row_list->qty_revisi == null)
                ? $row_list->qty
                : '<s style="color:#c0392b">'.$row_list->qty.'</s> <i class="fa fa-arrow-right" style="color:#888"></i> <b>'.$row_list->qty_revisi.'</b>';
            $stok_gudang = ($row_list->stok_gdg_revisi == null)
                ? number_format($row_list->jumlah_stok_gudang)
                : '<s style="color:#c0392b">'.number_format($row_list->jumlah_stok_gudang).'</s> <i class="fa fa-arrow-right" style="color:#888"></i> <b>'.number_format($row_list->stok_gdg_revisi).'</b>';

            $row[] = '<div class="left">'.$nama_brg.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan.'</div>';
            $row[] = '<div class="center">'.$stok_gudang.'</div>';
            $row[] = '<div class="center">'.number_format($row_list->jumlah_stok_sebelumnya).'</div>';
            $row[] = '<div class="center">'.$qty.'</div>';
            $row[] = '<div class="center"><b>'.$row_list->jml_acc_atasan.'</b></div>';

            // ── Kolom: Jml Kirim — input jika masih pending, teks jika sudah selesai ──
            if ($is_pending) {
                $_acc         = intval($row_list->jml_acc_atasan);
                $_id          = $row_list->id_tc_permintaan_inst_det;
                $_stok_kurang = ($stok_aktual < $_acc);
                // Jika stok gudang tidak mencukupi, default & maksimal = stok aktual
                $_max     = $_stok_kurang ? $stok_aktual : $_acc;
                $_default = $_stok_kurang ? $stok_aktual : $_acc;

                $input_class = 'qty-kirim-input' . ($_stok_kurang ? ' stok-kurang' : '');
                $input_html  = '<input type="number" '
                             . 'name="qty_kirim['.$_id.']" '
                             . 'id="qk_'.$_id.'" '
                             . 'class="'.$input_class.'" '
                             . 'data-id="'.$_id.'" '
                             . 'data-acc="'.$_acc.'" '
                             . 'data-stok="'.$stok_aktual.'" '
                             . 'data-max="'.$_max.'" '
                             . 'value="'.$_default.'" '
                             . 'min="1" max="'.$_max.'">';

                $warn_html = '';
                if ($_stok_kurang) {
                    $warn_html = '<div class="stok-warn-badge">'
                               . '<i class="fa fa-exclamation-triangle"></i> Stok tidak mencukupi<br>'
                               . '<span>Tersedia <b>'.$stok_aktual.'</b> &mdash; Disetujui <b>'.$_acc.'</b></span>'
                               . '</div>';
                }

                $row[] = '<div class="center">' . $input_html . $warn_html . '</div>';
            } else {
                $row[] = '<div class="center" style="font-weight:700;color:#15803d">'
                       . ($row_list->jumlah_kirim > 0 ? number_format($row_list->jumlah_kirim) : '<span style="color:#94a3b8">—</span>')
                       . '</div>';
            }
            // ────────────────────────────────────────────────────────────────

            // ── Kolom: Distribusi Terakhir ──────────────────────────────────
            if (isset($dist_map[$row_list->kode_brg])) {
                $d     = $dist_map[$row_list->kode_brg];
                $tgl_d = date('d/m/Y', strtotime($d->tgl_pengiriman));
                $row[] = '<div class="center" style="font-size:11px;line-height:1.5">'
                       . '<div style="color:#1a4f8a;font-weight:700">'.$tgl_d.'</div>'
                       . '<div style="color:#555">'.number_format($d->jumlah_kirim).' '.$row_list->satuan.'</div>'
                       . '</div>';
            } else {
                $row[] = '<div class="center" style="color:#94a3b8;font-size:11px">—</div>';
            }
            // ── Kolom: Jml Pasien & Mutasi (sejak distribusi terakhir) ─────
            if (isset($trans_map[trim($row_list->kode_brg)])) {
                $t        = $trans_map[trim($row_list->kode_brg)];
                $tgl_hari = date('d/m/Y');
                $row[] = '<div class="center" style="font-size:11px;line-height:1.6">'
                       . '<div><span style="color:#15803d;font-weight:700">'.intval($t->jml_pasien).'</span>'
                       . ' <span style="color:#777;font-size:10px">pasien</span></div>'
                       . '<div><span style="color:#1a4f8a;font-weight:700">'.number_format($t->jml_mutasi).'</span>'
                       . ' <span style="color:#777;font-size:10px">mutasi</span></div>'
                       . '<div style="color:#94a3b8;font-size:10px;margin-top:2px">'.$t->tgl_mulai.' &ndash; '.$tgl_hari.'</div>'
                       . '</div>';
            } else {
                $row[] = '<div class="center" style="color:#94a3b8;font-size:11px">—<br>'
                       . '<span style="font-size:10px">Belum ada<br>distribusi</span></div>';
            }
            // ───────────────────────────────────────────────────────────────

            // $row[] = '<div style="text-align:right;font-size:11px">'.number_format($row_list->harga).'</div>';
            $row[] = '<div class="center" style="font-size:11px">'.$row_list->keterangan_verif.'</div>';

            if($row_list->status_verif == 1){
                if($row_list->jumlah_kirim > 0){
                    $row[] = '<div class="center"><i class="fa fa-check" style="color:#15803d"></i></div>';
                }else{
                    $row[] = '<div class="center"><a class="label label-xs label-success" onclick="edit_brg('.$row_list->id_tc_permintaan_inst_det.', '."'Penyesuaian Permintaan Barang x Distribusi'".')" href="#"><i class="fa fa-pencil"></i></a></div>';
                }
            }else{
                $row[] = '<div class="center" style="color:#94a3b8">—</div>';
            }

            $data[] = $row;
        }

        $output = array(
            "total_belum_didistribusi" => array_sum($arr_count),
            "data"                     => $data,
        );
        echo json_encode($output);
    }

    public function delete_cart()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($id!=null){
            if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id))->delete('tc_permintaan_inst_cart_log')){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan' ));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function show_cart(){
        $data = array();
        $data['cart_data'] = $this->Pengiriman_unit->get_cart_data_log($_GET['form']);
        $data['form'] = ($_GET['form']=='retur')?$_GET['form']:'distribusi';
        // print_r($data);die;
        if($_GET['form'] == 'retur'){
            $this->load->view('pendistribusian/Pengiriman_unit/form_cart_retur', $data);
        }else{
            $this->load->view('pendistribusian/Pengiriman_unit/form_cart', $data);
        }
    }

    public function process_retur_brg_unit()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('dari_unit_hidden', 'Dari Unit', 'trim|required');
        $val->set_rules('tgl_retur', 'Tanggal', 'trim');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // nama bagian
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $val->set_value('dari_unit_hidden') ) );
            
            $kode_bagian_gudang = ($_POST['flag']=='medis')?'060201':'070101';
            $tc_po = ($_POST['flag']=='medis')?'tc_po':'tc_po_nm';
            $nama_gudang = ($_POST['flag']=='medis')?'Medis':'Non Medis';

            $dataexc = array(
                'kode_retur' => $this->master->format_nomor_retur($_POST['flag']),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('dari_unit_hidden'),'RGXQSL'),
                'tgl_retur' => $val->set_value('tgl_retur'),
                'status' => 1,
                'no_induk' => $this->session->userdata('user')->user_id,
                'tgl_input' => date('Y-m-d'),
                'petugas_unit' => $this->session->userdata('user')->fullname,
                'petugas_gudang' => $this->session->userdata('user')->fullname,
                'no_urut_periodik' => $this->master->get_max_number('tc_retur_unit','id_tc_retur_unit'),
                'status_selesai' => 1,
                'flag' => $_POST['flag'],
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
            );
            
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save('tc_retur_unit', $dataexc);
            /*save logs*/
            $this->logs->save('tc_retur_unit', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_retur_unit');
            
            // get data from cart
            $cart_data = $this->Pengiriman_unit->get_cart_data_log($_POST['flag_form']);
            // echo '<pre>';print_r($cart_data);die;
            
            foreach( $cart_data as $row_brg ){

                // retur penerimaan dan penjualan
                if( !empty($row_brg->reff_kode) || $row_brg->reff_kode != NULL ){

                    if($row_brg->retur_type == 'penerimaan_brg'){
                        // select penerimaan
                        $tc_penerimaan = ($_POST['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
                        $dt_penerimaan = $this->db->get_where($tc_penerimaan.'_detail', array('kode_detail_penerimaan_barang' => $row_brg->reff_kode, 'kode_brg' => $row_brg->kode_brg ) )->row();
                        // convert to satuan besar
                        $jml_convert = $row_brg->qty / $dt_penerimaan->content;
                        if(!empty($dt_penerimaan)){
                            $jml_retur = $dt_penerimaan->jumlah_kirim - $jml_convert;
                            $jml_retur_decimal = $dt_penerimaan->jumlah_kirim_decimal - $jml_convert;
                            // print_r($jml_convert);die;
                            // update data penerimaan
                            $this->db->update($tc_penerimaan.'_detail', array('jumlah_kirim' => $jml_retur, 'jumlah_kirim_decimal' => $jml_retur_decimal, 'keterangan' => 'Retur barang '.$jml_convert.'' ), array('kode_detail_penerimaan_barang' => $row_brg->reff_kode, 'kode_brg' => $row_brg->kode_brg) );
                            // kurang stok gudang
                            $konversi = $row_brg->qty;
                            $this->stok_barang->stock_process($row_brg->kode_brg, $konversi, $kode_bagian_gudang, 21 ," ".$nama_gudang." Nomor ".$row_brg->reff_kode." ", 'reduce');
                        }
                        $catatan = 'Retur penerimaan kode '. $row_brg->reff_kode.'';
                        // update status selesai po
                        $this->db->where('id_tc_po IN (SELECT id_tc_po FROM '.$tc_po.'_det WHERE id_tc_po_det = '.$dt_penerimaan->id_tc_po_det.')')->update($tc_po, array('status_selesai' => NULL));
                    }

                    if($row_brg->retur_type == 'penjualan_brg'){
                        // select tr resep
                        $cek_non_racikan = $this->db->get_where('fr_tc_far_detail', array('kd_tr_resep' => $row_brg->reff_kode) )->row();
                        if(empty($cek_non_racikan)){
                            $resep = $this->db->select('id_tc_far_racikan_detail, jumlah as qty, harga_jual')->get_where('fr_obat_racikan_v', array('id_tc_far_racikan_detail' => $row_brg->reff_kode) )->row();
                            // racikan
                            // echo '<pre>';print_r($resep);die;
                            $harga_retur = $row_brg->qty * $resep->harga_jual;
                            // tc_far_racikan_detail
                            $this->db->where(array('id_tc_far_racikan_detail' => $row_brg->reff_kode))->update('tc_far_racikan_detail', array('jumlah_retur' => $row_brg->qty));
                            // retur stok ke farmasi
                            if($row_brg->is_restock == 1){
                                // retur stok ke farmasi
                                $this->stok_barang->stock_process_racikan($row_brg->kode_brg, (int)$row_brg->qty, '060101', 8 ," (Retur Penjualan), Kode obat racikan: ".$row_brg->reff_kode." ", 'restore');
                            }
                        }else{
                            // non racikan
                            $resep = $cek_non_racikan;
                            $harga_retur = $row_brg->qty * $resep->harga_jual;
                            $data_for_update = array(
                                'jumlah_retur' => $row_brg->qty,
                                'harga_r_retur' => $harga_retur,
                                'status_retur' => 1,
                            );
                            // fr_tc_far
                            $this->db->where(array('kd_tr_resep' => $row_brg->reff_kode))->update('fr_tc_far_detail', $data_for_update);

                            $data_log_for_update = array(
                                'jumlah_retur' => $row_brg->qty,
                                'is_restock' => $row_brg->is_restock,
                                'tgl_retur' => date('Y-m-d'),
                                'retur_by' => $this->session->userdata('user')->fullname,
                            );
                            
                            $this->db->where(array('relation_id' => $row_brg->reff_kode))->update('fr_tc_far_detail_log', $data_log_for_update);

                            // retur stok ke farmasi
                            if($row_brg->is_restock == 1){
                                $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, '060101' , 8 ," (Retur Penjualan) - . ".$row_brg->reff_kode." ", 'restore'); 
                            }
                        }

                        $this->db->trans_commit();

                    }

                    // barang expired
                    if($row_brg->retur_type == 'expired'){
                        $jml_retur = $jml_convert;
                        $jml_retur_decimal = $jml_convert;
                        // print_r($jml_convert);die;
                        // kurang stok gudang
                        $konversi = $row_brg->qty;
                        $this->stok_barang->stock_process($row_brg->kode_brg, $konversi, $kode_bagian_gudang, 2 ,'Retur Barang ke Supplier', 'restore');
                    }
                    

                }else{

                    // tambah stok gudang
                    $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, $kode_bagian_gudang, 4 ,"", 'restore');

                    // kurang stok depo
                    $this->stok_barang->stock_process_depo($row_brg->kode_brg, $row_brg->qty, $kode_bagian_gudang, 4 ,"", 'reduce', $val->set_value('dari_unit_hidden'));
                    
                }

                // insert detail barang
                $dt_detail = array(
                    'kode_retur' => $this->regex->_genRegex($dataexc['kode_retur'],'RGXQSL'),
                    'kode_brg' => $this->regex->_genRegex($row_brg->kode_brg,'RGXQSL'),
                    'jumlah' => $row_brg->qty,
                    'jml_sebelum' => $row_brg->qtyBefore,
                    'alasan' => isset($catatan) ? $catatan : 'Retur barang karena kesalahan stok',
                    'id_tc_retur_unit' => $this->regex->_genRegex($newId,'RGXINT'),
                );
                
                $dt_detail['created_date'] = date('Y-m-d H:i:s');
                $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $id_tc_retur_unit_det = $this->Pengiriman_unit->save('tc_retur_unit_det', $dt_detail);
                /*save logs*/
                $this->logs->save('tc_retur_unit_det', $id_tc_retur_unit_det, 'insert new record on '.$this->title.' module', json_encode($dt_detail),'id_tc_retur_unit_det');
                
                $this->db->trans_commit();

            }

            // delete cart session
            $this->db->delete('tc_permintaan_inst_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => 'retur') );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId));
            }
        }
    }

    public function insert_cart_log(){
        // print_r($_POST);die;
        // search data barang by barcode
        $barcode = $this->Pengiriman_unit->checkBarcode($_POST['barcode'], $_POST['flag']);

        if ( empty($barcode)) {

            echo json_encode( array('status' => 300, 'message' => 'Barang tidak ditemukan') ); exit;

        }else{

            if( empty($_POST['qty'])){
                echo json_encode( array('status' => 300, 'message' => 'Masukan qty !') ); exit;
            }

            $this->db->trans_begin();
            $total = $_POST['qty'] * (float)$_POST['harga'];
            $kode_bagian = isset($_POST['dari_unit'])?$_POST['dari_unit']:'';
            $dataexc = array(
                'kode_brg' => $_POST['kode_brg'],
                'nama_brg' => $_POST['nama_brg'],
                'qty' => (float)$_POST['qty'],
                'qtyBefore' => (float)$_POST['qtyBefore'],
                'satuan' => $_POST['satuan'],
                'harga' => (float)$_POST['harga'],
                'total' => (float)$total,
                'user_id_session' => $this->session->userdata('user')->user_id,
                'flag' => $_POST['flag'],
                'barcode' => $_POST['barcode'],
                'kode_bagian' => isset($_POST['kode_bagian']) ? $_POST['kode_bagian'] : $kode_bagian,
                'flag_form' => isset($_POST['flag_form'])?$_POST['flag_form']:'',
                'reff_kode' => isset($_POST['reff_kode'])?$_POST['reff_kode']:'',
                'retur_type' => isset($_POST['retur_type'])?$_POST['retur_type']:'',
                'is_bhp' => isset($_POST['is_bhp'])?$_POST['is_bhp']:'',
                'is_restock' => isset($_POST['restock'])?$_POST['restock']:1,
            );
            // print_r($dataexc);die;
            $this->db->insert('tc_permintaan_inst_cart_log', $dataexc);
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $dataexc['flag'], 'kode_brg' => $dataexc['kode_brg'], 'nama_brg' => $dataexc['nama_brg']));
        }

    }

    public function show_penerimaan_brg()
    {
        $id = $_GET['ID'];
        $t_penerimaan = ($_GET['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['id_penerimaan_existing'] = $id;
        $data['value'] = $this->db->get_where($t_penerimaan, array('id_penerimaan' => $id) )->row();
        /*load form view*/
        $this->load->view('penerimaan/Penerimaan_brg/view_penerimaan_brg', $data);
    }

    public function show_detail_brg()
    {
        $result = $this->Pengiriman_unit->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        // print_r($this->db->last_query());die;
        $data['id'] = $_GET['ID'];
        $data['flag'] = $_GET['flag'];
        $data['value'] = $result;
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/view_detail_brg', $data);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_bagian_minta', 'Bagian/Unit', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // nama bagian
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $val->set_value('kode_bagian_minta') ) );
            
            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            $kode_bagian = ($_POST['flag']=='medis')?'060201':'070101';

            $dataexc = array(
                'tgl_permintaan' => date('Y-m-d'),
                'nomor_permintaan' => $this->regex->_genRegex($this->master->format_nomor_permintaan($_POST['flag']),'RGXQSL'),
                'kode_bagian_minta' => $this->regex->_genRegex($val->set_value('kode_bagian_minta'),'RGXQSL'),
                'kode_bagian_kirim' => $this->regex->_genRegex($kode_bagian,'RGXQSL'),
                'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
            );
            // print_r($dataexc);die;
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save($table, $dataexc);
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            

            foreach( $_POST['kode_brg'] as $row_brg ){

                // insert detail barang
                $dt_detail = array(
                    'id_tc_permintaan_inst' => $this->regex->_genRegex($newId,'RGXINT'),
                    'jumlah_permintaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'kode_brg' => $this->regex->_genRegex($row_brg,'RGXQSL'),
                    'satuan' => $this->regex->_genRegex($_POST['satuan'][$row_brg],'RGXQSL'),
                    'tgl_kirim' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'jumlah_penerimaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'kekurangan' => $this->regex->_genRegex(0,'RGXQSL'),
                    'jml_acc_atasan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'jml_acc_umu' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                );
                
                $dt_detail['created_date'] = date('Y-m-d H:i:s');
                $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $id_permintaan_inst_det = $this->Pengiriman_unit->save($table.'_det', $dt_detail);
                /*save logs*/
                $this->logs->save($table.'_det', $id_permintaan_inst_det, 'insert new record on '.$this->title.' module', json_encode($dt_detail),'id_tc_permintaan_inst_det');
                
                // kurang stok gudang
                $this->stok_barang->stock_process($row_brg, $_POST['total_dikirim'][$row_brg], $kode_bagian, 3 ," ".$nama_bagian." ", 'reduce');

                // tambah stok depo
                $this->stok_barang->stock_process_depo($row_brg, $_POST['total_dikirim'][$row_brg], $kode_bagian, 3 ," ".$nama_bagian." ", 'restore', $val->set_value('kode_bagian_minta'));

                
                // update header permintaan_inst
                $dt_upd_permintaan = array(
                    'kode_bagian_kirim' => $kode_bagian,
                    'status_batal' => 0,
                    'nomor_pengiriman' => $newId,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl_pengiriman' => date('Y-m-d H:i:s'),
                    'yg_serah' => $this->session->userdata('user')->fullname,
                    'yg_terima' => 'Staf '.$nama_bagian,
                    'tgl_input_terima' => date('Y-m-d H:i:s'),
                    'keterangan_kirim' => 'Distribusi langsung dari penerimaan barang',
                    'status_selesai' => 4,
                    'jenis_permintaan' => 0,
                    // 'jml_acc_atasan' => $_POST['total_dikirim'][$row_brg],
                    // 'jml_acc_umu' => $_POST['total_dikirim'][$row_brg],
                    'no_kirim' => $newId,
                    'no_urut' => $newId,
                    'no_acc' => 'ACC/'.$newId.'',
                    'tgl_acc' => date('Y-m-d H:i:s'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );
                
                $this->Pengiriman_unit->update($table, array('id_tc_permintaan_inst' => $newId), $dt_upd_permintaan );
                /*save logs*/
                $this->logs->save($table, $newId, 'update record on '.$this->title.' module', json_encode($dt_upd_permintaan),'id_tc_permintaan_inst');

                $this->db->trans_commit();

            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId));
            }
        }
    }



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */

