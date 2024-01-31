<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Resep_ri extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Resep_ri');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Resep_ri_model', 'Resep_ri');
        $this->load->model('Farmasi_pesan_resep_model', 'Farmasi_pesan_resep');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        $this->load->library('daftar_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $is_icu = (isset($_GET['is_icu']))?$_GET['is_icu']:'N';
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'is_icu' => $is_icu,
        );

        $this->load->view('Resep_ri/index', $data);
    }

    public function form($kode_ri='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Resep_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_ri);
        /*get value by id*/
        $data['value'] = $this->Resep_ri->get_by_id($kode_ri);
        // echo '<pre>';print_r($data['value']);die;
        $data['riwayat'] = $this->Resep_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['transaksi'] = $this->Resep_ri->get_transaksi_pasien_by_id($no_kunjungan);
        $data['ruangan'] = $this->Resep_ri->get_ruangan_by_id($data['value']->kode_ruangan);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_ri;
        $data['kode_klas'] = $data['value']->kelas_pas;
        $data['klas_titipan'] = ($data['value']->kelas_titipan!=NULL)?$data['value']->kelas_titipan:0;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Resep_ri/form', $data);
    }

    public function pesan_resep($no_kunj='',$kode_klas='',$kode_profit='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Farmasi_pesan_resep/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_kunj);
        /*get value by no_kunj*/
        $data['value'] = $this->Farmasi_pesan_resep->get_detail_data_kunjungan($no_kunj);
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = $kode_profit;
        $data['kode_bagian_asal'] = isset($_GET['kode_bag'])?$_GET['kode_bag']:'';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Resep_ri/form_pesan_resep', $data);
    }


    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Resep_ri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            // $cek_trans = $this->Resep_ri->cek_trans_pelayanan($row_list->no_registrasi);

            $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';

            /*color of type Ruangan RI*/
            /*LB*/
            if ( in_array($row_list->bag_pas, array('030101','031401','031301','030801','030401','031601') ) ) {
                $color = 'red';
            /*LA*/
            }elseif( in_array($row_list->bag_pas,  array('030701','030301','030601','030201') )){
                $color = 'green';
            /*VK Ruang Bersalin*/
            }elseif( in_array($row_list->bag_pas,  array('031201','031701','030501') )){
                $color = 'blue';
            }else{
                $color = 'black';
            }

            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' 
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Resep_ri/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<span style="color:'.$color.'"><b>'.strtoupper($row_list->nama_pasien).'</b></span>';
            $row[] = $row_list->nama_bagian;
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $row_list->klas;
            $row[] = ($row_list->klas_titip)?$row_list->klas_titip:'-';
            $row[] = number_format($row_list->tarif_inacbgs);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;

            if($row_list->status_pulang == 0 || $row_list->status_pulang == null){
                if($row_list->pasien_titipan == 1){
                    $status = '<label class="label label-yellow">Pasien Titipan</label>';
                }else{
                    $status = '<label class="label label-danger">Masih dirawat</label>';
                }
            }else{
                $status = '<label class="label label-success">Pulang</label>';
            }

            // if($cek_trans==0){
            //     $status_pulang = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            // }else{
            //     $status_pulang = ($row_list->status_pulang == 0 || NULL) ? ($row_list->pasien_titipan== 1) ? '<label class="label label-yellow">Pasien Titipan</label>':'<label class="label label-danger">Masih dirawat</label>':'<label class="label label-success">Pulang</label>';
            // }

            $row[] = '<div class="center">'.$status.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Resep_ri->count_all(),
                        "recordsFiltered" => $this->Resep_ri->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_by_id()
    {
        /*get data from model*/
        $list = $this->Farmasi_pesan_resep->get_by_no_kunj($this->input->get('q'));
        // print_r($this->db->last_query());
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '<div class="center">
                        <a href="#" id="btn_edit_data" class="btn btn-xs btn-default" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=RI'".')">Entry Resep</a>
                    </div>'; 
            $row[] = '<div class="left">'.$this->tanggal->formatDateTime($row_list->tgl_pesan).'</div>';
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->kode_pesan_resep;
            $row[] = $row_list->kode_trans_far;
            $row[] = $row_list->nama_pegawai;
            $row[] = ($row_list->lokasi_tebus==1)?'Dalam RS':'Luar RS';
            $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            $label_retur = ($row_list->total_retur > 0) ? '<a href="#" onclick="show_modal_medium_return_json('."'farmasi/Retur_obat/show_retur_data/".$row_list->kode_pesan_resep."?no_mr=".$row_list->no_mr."'".', '."'HISTORY RETUR FARMASI'".')" ><span class="label label-yellow label-badge">'.$row_list->total_retur.'</span></a>' : '-' ;
            $row[] = '<div class="center">'.$label_retur.'</div>';
            $status_tebus = ($row_list->status_tebus==null)?'<label class="label label-danger">Dalam Proses</label>':'<label class="label label-success">Selesai</label>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Farmasi_pesan_resep->count_all_id($this->input->get('q')),
                        "recordsFiltered" => $this->Farmasi_pesan_resep->count_all_id($this->input->get('q')),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function getDetail($id){
        
        $data = $this->Farmasi_pesan_resep->get_detail_by_id($id);
        
        $html = '';
        $html_btn = '';
        if(count($data) > 0){
            $his_retur = $this->Retur_obat->get_history_retur($data[0]->kode_trans_far);
            // echo '<pre>';print_r($his_retur);die;

            $html .= '<br>';
            if($data[0]->kode_tc_trans_kasir==null){
                if($data[0]->status_transaksi == 1){
                    $html_btn .= '<a href="#" onclick="PopupCenter('."'farmasi/Process_entry_resep/nota_farmasi/".$data[0]->kode_trans_far."'".')" class="btn btn-xs btn-warning"><i class="fa fa-print dark"></i> Nota Farmasi</a> <a href="#" onclick="rollback_transaksi('.$data[0]->kode_trans_far.', '.$data[0]->kode_pesan_resep.')" class="btn btn-xs btn-danger"><i class="fa fa-undo dark"></i> Rollback Transaksi</a> <a href="#" onclick="proses_retur_obat('.$data[0]->kode_trans_far.')" class="btn btn-xs btn-primary"><i class="fa fa-save dark"></i> Proses Retur Obat</a>';
                }
                
            }
            $html .= '<div class="left">
                        <b>RESEP FARMASI RAWAT INAP</b><br>
                        No. <a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$id."?mr=".$_GET['no_mr']."&tipe_layanan=RI'".')">'.$data[0]->kode_trans_far.'</a> - '.$data[0]->no_resep.'
                        <div class="pull-right" style="margin-top: -10px">
                            '.$html_btn.'
                        </div>
                      </div>';
            $html .= '<br>';
            $html .= '<form id="form_retur_'.$data[0]->kode_trans_far.'" method="post" action="farmasi/Farmasi_pesan_resep/retur_obat">';
            $html .= '<input type="hidden" value="'.$data[0]->kode_trans_far.'" name="kode_trans_far">';
            $html .= '<table class="table">';
            $html .= '<thead>';
            $html .= '<tr>';
                $html .= '<th>Tanggal Transaksi</th>';
                $html .= '<th>Nama Barang</th>';
                $html .= '<th width="100px">Status Kasir</th>';
                $html .= '<th class="center" width="70px">Jml Obat</th>';
                $html .= '<th class="center" width="70px">Ttl Retur</th>';
                $html .= '<th class="center" width="70px">Jml Retur</th>';
                $html .= '<th class="center">Total</th>';
            $html .= '</tr>'; 
            $html .= '</thead>'; 
            $total=0;
            $total_jumlah=0;
            $html .= '<tbody>';
            foreach ($data as $value_data) {
                if( $value_data->jumlah_tebus > 0 ) :
                    $html .= '<tr>';
                        $html .= '<td align="left">'.$this->tanggal->formatDateTime($value_data->tgl_trans).'</td>';
                        $html .= '<td>'.$value_data->nama_brg.'</td>';
                        $status_trans = ($value_data->kode_tc_trans_kasir==null)?'<label class="label label-yellow">Belum bayar</label>':'<label class="label label-primary">Lunas</label>';
                        $html .= '<td align="center">'.$status_trans.'</td>';
                        $html .= '<td class="center">'.number_format($value_data->jumlah_tebus).'</td>';
                        $html .= '<td class="center">'.number_format($value_data->jumlah_retur).'</td>';
                        $html .= '<td class="center"><input type="text" name="retur['.$value_data->kd_tr_resep.']" class="form-control" style="width: 70px; text-align: center"></td>';
                        $harga = ($value_data->jumlah_tebus > 0) ? $value_data->biaya_tebus + $value_data->harga_r : 0;
                        $html .= '<input type="hidden" name="jml_tebus['.$value_data->kd_tr_resep.']" class="form-control" value="'.$value_data->jumlah_tebus.'">';
                        $html .= '<input type="hidden" name="harga_jual['.$value_data->kd_tr_resep.']" class="form-control" value="'.$harga.'">';
                        $html .= '<input type="hidden" name="kode_brg['.$value_data->kd_tr_resep.']" class="form-control" value="'.$value_data->kode_brg.'">';
                        $html .= '<td align="right">'.number_format($harga).'</td>';
                    $html .= '</tr>';
                    $total_jumlah += $value_data->jumlah_tebus;
                    $total += $harga;
                endif;
            }
            $html .= '</tbody>';
            $html .= '<tr>';
                $html .= '<td colspan="6" style="text-align:right; font-weight: bold">Total Biaya</td>';
                // $html .= '<td class="center">'.number_format($total_jumlah).'</td>';
                $html .= '<td align="right"><b>'.number_format($total).'</b></td>';
            $html .= '</tr>'; 
            
            $html .= '</table>'; 
            $html .= '</form>'; 
            if(count($his_retur) > 0){
                $html .= '<br><b>RIWAYAT RETUR OBAT</b><br><br>';
                foreach ($his_retur as $khr => $vhr) {
                    $html .= 'No. Retur : '.$khr.' &nbsp;&nbsp;&nbsp; Tgl. '.$this->tanggal->formatDateTimeFormDmy($vhr[0]->tgl_his_retur).'';
                    $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="PopupCenter('."'farmasi/Retur_obat/nota_retur/".$khr."'".')"><i class="fa fa-print dark bigger-150"></i> </a>';
                    $html .= '<table class="table" style="width: 50%">';
                    $html .= '<thead>';
                    $html .= '<tr>';
                    $html .= '<th class="center">No</th><th>Nama Obat</th><th width="100px">Jumlah Retur</th><th class="center">Subtotal</th><th class="center">Undo</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $no = 1;
                    foreach ($vhr as $k_sub_dt => $v_sub_dt) {
                        # code...
                        $html .= '<tr>';
                        $html .= '<td align="center">'.$no.'</td>';
                        $html .= '<td>'.$v_sub_dt->nama_brg.'</td>';
                        $html .= '<td align="center">'.$v_sub_dt->jumlah_retur_his.'</td>';
                        $html .= '<td align="right">'.number_format($v_sub_dt->biaya_retur_his).'</td>';
                        $html .= '<td align="center"><a href="#" onclick="undo_retur('.$v_sub_dt->kd_his.', '.$v_sub_dt->kd_tr_resep.', '.$value_data->kode_trans_far.')" class="btn btn-xs btn-danger"><i class="fa fa-undo"></i></a></td>';
                        $html .= '</tr>';
                        $no++;
                    }
                    $html .= '</table>';
                }
            }
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Belum diproses</b></div><br>';
        }
        
        echo json_encode(array('html' => $html));
    }


































































    

    public function pesan($id='', $no_registrasi='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Resep_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Resep_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $data['value']->no_kunjungan;
        $data['no_registrasi'] = $no_registrasi;
        $data['kode_ri'] = $id;
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Resep_ri/form_pesan', $data);
    }

    

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }


    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->input->post('type')==1){
                if($this->Resep_ri->delete_by_id('ri_pasien_vk','id_pasien_vk',$id)){
                    echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                }
            }else if($this->input->post('type')==2){
                if($this->Resep_ri->delete_by_id('ri_pesan_bedah','id_pesan_bedah',$id)){
                    echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                }
            } 
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*update tc_registrasi*/
        // $reg_data = array('tgl_jam_keluar' => NULL, 'kode_bagian_keluar' => NULL, 'status_batal' => NULL );
        // $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        // $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $data_ri = array('tgl_keluar' => NULL, 'status_pulang' => 0, 'user_plg' => NULL );
        $this->db->update('ri_tc_rawatinap', $data_ri, array('no_kunjungan' => $_POST['no_kunjungan']) );
        //$this->logs->save('ri_tc_rawatinap', $_POST['no_kunjungan'], 'update ri_tc_rawatinap Modul Pelayanan', json_encode($data_ri),'no_kunjungan');

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );
        $this->db->delete('tc_trans_pelayanan', array('no_kunjungan' => $_POST['no_kunjungan'], 'jenis_tindakan' => 2, 'nama_tindakan' => 'Biaya Administrasi'));


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ) );
        }
        
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
