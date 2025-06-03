<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rm_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'rekam_medis/Rm_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Rm_pasien_model', 'Rm_pasien');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
        /*load module*/
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Resume Medis Pasien',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Rm_pasien/index', $data);
    }

    public function form($no_registrasi, $tipe='RJ')
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Rm_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        $registrasi = $this->Csm_billing_pasien->get_by_id($no_registrasi);
        /*load form view*/
        $data['no_registrasi'] = $no_registrasi;
        $data['value'] = $registrasi;
        $data['title'] = 'Resume Medis Pasien';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['reg'] = $this->Rm_pasien->get_by_id($no_registrasi);
        // echo '<pre>';print_r($data);die;
        $this->load->view('Rm_pasien/form_edit', $data);
    }

    public function form_data_pasien($no_mr)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Rm_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_mr);
        /*define data variabel*/
        $data_pasien = $this->db->get_where('mt_master_pasien', array('no_mr' => $no_mr))->row();
        /*load form view*/
        $data['value'] = $data_pasien;
        $data['title'] = 'Data Pasien';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Rm_pasien/form_data_pasien', $data);
    }

    public function form_diagnosa($no_registrasi, $tipe='RJ')
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Rm_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        $registrasi = $this->Csm_billing_pasien->get_by_id($no_registrasi);
        /*load form view*/
        $data['no_registrasi'] = $no_registrasi;
        $data['riwayat'] = $this->Rm_pasien->get_riwayat_pasien_by_id($registrasi);
        $data['value'] = $registrasi;
        $data['title'] = 'Resume Medis Pasien';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['attachment'] = $this->upload_file->CsmgetUploadedFile($no_registrasi);
        $data['reg'] = $this->Rm_pasien->get_by_id($no_registrasi);
        //echo '<pre>';print_r($data);die;
        $this->load->view('Rm_pasien/form_diagnosa', $data);
    }

    public function riwayat_kunjungan($no_mr, $kode_bagian='') { 
        
        $data = [
            'no_mr' => $no_mr,
            'kode_bagian' => $kode_bagian,
        ];
        $this->load->view('Rm_pasien/tab_riwayat_kunjungan', $data);
    
    }

    public function riwayat_perjanjian($no_mr, $kode_bagian='') { 
        
        $data = [
            'no_mr' => $no_mr,
            'kode_bagian' => $kode_bagian,
        ];
        $this->load->view('Rm_pasien/tab_riwayat_perjanjian', $data);
    
    }

    public function get_riwayat_pasien() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();
        $output = array();

        $column = array('tc_kunjungan.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_perusahaan','tc_kunjungan.tgl_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_kunjungan.tgl_keluar','tc_kunjungan.kode_bagian_tujuan','mt_perusahaan.nama_perusahaan','tc_kunjungan.no_kunjungan', 'mt_master_pasien.nama_pasien', 'mt_master_pasien.no_mr');

        $list = $this->Reg_pasien->get_riwayat_pasien( $column, $mr ); 

        $no = 0;

        $atts = array('width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            
            $no++;
            
            $row = array();

            /*status pasien*/
            $status = ($row_list->tgl_keluar==NULL)?'<div class="center"><label class="label label-danger">Proses Menunggu...</label></div>':'<div class="center"><label class="label label-success">Sudah Pulang</label></div>';  
            $status_icon = ($row_list->tgl_keluar==NULL)?'<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>':'<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';  

            $delete_registrasi = ($row_list->tgl_keluar==NULL)?'<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>':'';  
            
            /*btn hasil pm*/
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);
            $btn_view_hasil_pm = ($subs_kode_bag=='05')?'<li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_view_hasil_pm/".$row_list->no_registrasi."/".$row_list->no_kunjungan."'".', '."'HASIL PENUNJANG MEDIS (".$row_list->nama_bagian.")'".')">Lihat Hasil '.$row_list->nama_bagian.'</a></li>':'';
            $btn_print_out_checklist_mcu = '';
            /*btn for medical checkup*/
            if( $row_list->kode_bagian_tujuan=='010901' ){
                /*get data from trans_pelayanan*/
                $dt_trans_mcu = $this->db->get_where('tc_trans_pelayanan', array('no_registrasi' => $row_list->no_registrasi, 'no_kunjungan' => $row_list->no_kunjungan) )->row();
                $btn_print_out_checklist_mcu = '<li><a href="#" onclick="PopupCenter('."'registration/Reg_mcu/print_checklist_mcu?kode_tarif=".$dt_trans_mcu->kode_tarif."&nama=".$dt_trans_mcu->nama_pasien_layan."&no_mr=".$dt_trans_mcu->no_mr."&no_reg=".$row_list->no_registrasi."'".', '."'FORM CHEKLIST MCU'".', 850, 500)">Cetak Form Cheklist MCU</a></li>';
            }

            if($row_list->nama_perusahaan==''){
                $penjamin = 'Umum';
            }else if(($row_list->nama_perusahaan!='') AND ($row_list->kode_perusahaan==120) AND ($row_list->no_sep!='')){
                $penjamin = $row_list->nama_perusahaan.' ('.$row_list->no_sep.')';
            }else{
                $penjamin = $row_list->nama_perusahaan;
            }

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="PopupCenter('."'registration/Reg_klinik/print_bukti_pendaftaran_pasien?nama=".$row_list->nama_pasien."&no_mr=".$row_list->no_mr."&no_reg=".$row_list->no_registrasi."&poli=".$row_list->nama_bagian."&dokter=".$row_list->nama_pegawai."&nasabah=".$penjamin."'".', '."'FORM BUKTI PENDAFTARAN PASIEN'".', 950, 550)">Cetak Bukti Pendaftaran</a></li>
                            <li>'.anchor_popup('registration/reg_pasien/tracer/'.$row_list->no_registrasi.'/'.$mr.'', 'Cetak Tracer', $atts).'</li>
                            <li class="divider"></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            
            $nama_dokter = ($row_list->nama_pegawai != '') ? $row_list->nama_pegawai.'<br>' : '' ;

            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = $penjamin;
            $row[] = $status;

            // $row[] = anchor_popup('registration/reg_pasien/tracer/'.$row_list->no_registrasi.'/'.$mr.'', '<div class="center"><button class="btn btn-xs btn-inverse" ><i class="fa fa-print"></i></button></div>', $atts);
          
            $data[] = $row;
        
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function get_riwayat_perjanjian() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();

        $output = array();

        $column = array('tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day');

        $list = $this->Reg_pasien->get_riwayat_perjanjian( $column, $mr ); 

        $no = 0;

        $atts = array(
                    'width'       => 900,
                    'height'      => 500,
                    'scrollbars'  => 'no',
                    'status'      => 'no',
                    'resizable'   => 'no',
                    'screenx'     => 1000,
                    'screeny'     => 80,
                    'window_name' => '_blank'
            );

            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $html = '';

                if( isset($_GET['no_mr']) AND $_GET['no_mr'] != '' ){
                    $html .= '<li><a href="#" onclick="changeModulRjFromPerjanjian('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">Daftarkan Pasien</a></li>';
                }else{
                    $html .= '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?idp=".$row_list->id_tc_pesanan."&kode_dokter=".$row_list->kode_dokter."&poli=".$row_list->no_poli."&kode_perjanjian=".$row_list->kode_perjanjian."&no_mr=".$row_list->no_mr."'".')">Daftarkan Pasien</a></li>';
                }

                if( isset($_GET['flag']) AND $_GET['flag']=='HD' ){
                    $tgl = $row_list->selected_day;
                }else{
                    $tgl = $this->tanggal->formatDate($row_list->tgl_pesanan);
                }
                $penjamin = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';

                $label_code = ($row_list->tgl_masuk == NULL) ? '<div class="pull-right"><span class="label label-sm label-danger">'.$row_list->kode_perjanjian.'</span></div>' : '<div class="pull-right"><span class="label label-sm label-success">'.$row_list->kode_perjanjian.'</span></div>';

                $row[] = '<div class="center">'.$no.'</div>';
                $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                '.$html.'
                                <li><a href="#" onclick="cetak_surat_kontrol('.$row_list->id_tc_pesanan.')">Cetak Surat Kontrol</a></li>
								<li><a href="#" onclick="delete_perjanjian('.$row_list->id_tc_pesanan.')" >Hapus</a></li>
                            </ul>
                        </div></div>';
                $row[] = ucwords($row_list->nama_bagian);
                $row[] = $row_list->nama_pegawai;
                $row[] = $tgl;
                $row[] = $penjamin;
                $row[] = $row_list->kode_perjanjian;
                $row[] = ($row_list->tgl_masuk == NULL) ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i></span></div>' : '<div class="center"><span class="label label-sm label-success"><i class="fa fa-check"></i></span></div>';


                $data[] = $row;
            }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_perjanjian_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Rm_pasien->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = $row_list->no_registrasi;
            $row[] = '';
            $row[] = '';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><b><a href="#" onclick="getMenu('."'".'rekam_medis/Rm_pasien/form/'.$row_list->no_registrasi.''."'".')">'.$row_list->no_mr.'</a></b></div>';
            $row[] = strtoupper($row_list->nama_pasien).' ('.$row_list->jen_kelamin.')<br>TL. '.$this->tanggal->formatDate($row_list->tgl_lhr).' ('.$row_list->umur.') Thn';
            $row[] = $row_list->nama_pegawai.'<br>'.strtoupper($row_list->nama_bagian);
            // detail bpjs
            $pf = '';
            if($row_list->kode_perusahaan == 120){
                $pf .= '<br>NOKA. '.$row_list->no_kartu_bpjs.'<br>';
                $pf .= 'NO SEP. '.$row_list->no_sep.'';
            }
            $row[] = $row_list->nama_perusahaan.''.$pf;
            $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_masuk).'<br><i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_keluar);
            $row[] = $row_list->diagnosa_rujukan;
            $data[] = $row;
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Rm_pasien->count_all(),
                    "recordsFiltered" => $this->Rm_pasien->count_filtered(),
                    "data" => $data,
        );
    
        
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Rm_pasien->get_data();
        //output to json format
        return $list;
    }

    public function html_content($data)
    {
        /*get data from model*/
        $html = ' <table border="1">
                        <thead>
                          <tr>  
                            <th width="30px" align="center">No</th>
                            <th>No. SEP</th>
                            <th>No. MR</th>
                            <th width="150px">Nama Pasien</th>
                            <th width="150px">Tanggal Transaksi</th>
                            <th width="70px">Tipe (RI/RJ)</th>
                            <th width="70px" align="center">Total Klaim</th>
                          </tr>
                        </thead>
                        <tbody>';
        $no = 0;
        foreach ($data as $key => $value) { $no++;
            $html .= '<tr>  
                        <td width="30px" align="center">'.$no.'</td>
                        <td>'.$value->csm_rp_no_sep.'</td>
                        <td>'.$value->csm_rp_no_mr.'</td>
                        <td width="150px">'.$value->csm_rp_nama_pasien.'</td>
                        <td width="150px">'.$value->tgl_transaksi_kasir.'</td>
                        <td width="70px" align="center">'.$value->csm_dk_tipe.'</td>
                        <td width="70px" align="right">'.number_format($value->csm_dk_total_klaim).'</td>
                      </tr>';
                      $arr_subtotal[] = $value->csm_dk_total_klaim;
                      $tot_pasien_ri[] = ($value->csm_dk_tipe=='RI')?1:0;
                      $tot_pasien_rj[] = ($value->csm_dk_tipe=='RJ')?1:0;
                      $tot_pasien_ri_bill[] = ($value->csm_dk_tipe=='RI')?$value->csm_dk_total_klaim:0;
                      $tot_pasien_rj_bill[] = ($value->csm_dk_tipe=='RJ')?$value->csm_dk_total_klaim:0;
        }
        $html .= '<tr>  
                        <td colspan="6" align="center"></td>
                        <td width="70px" align="right">'.number_format(array_sum($arr_subtotal)).'</td>
                      </tr>';

        $html .= '</tbody>
                  </table>';

        $html .= '<br>';
        $html .= '<p style="font-size:12px"><b>Resume Klaim :</b></p>';
        $html .= '<table border="1">
                    <tr>  
                        <th colspan="3">Jenis Pasien</th>
                        <th>Total Pasien</th>
                        <th>Total Klaim</th>
                      </tr>

                      <tr>  
                        <td colspan="3">Pasien Rawat Inap (RI)</td>
                        <td>'.array_sum($tot_pasien_ri).'</td>
                        <td>'.number_format(array_sum($tot_pasien_ri_bill)).'</td>
                      </tr>
                      <tr>  
                        <td colspan="3">Pasien Rawat Jalan (RJ)</td>
                        <td>'.array_sum($tot_pasien_rj).'</td>
                        <td>'.number_format(array_sum($tot_pasien_rj_bill)).'</td>
                      </tr>

                <tbody>';

        $html .= '</tbody>
                  </table>';

        //output to json format
        return $html;
    }

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('no_registrasi_hidden', 'No Registrasi', 'trim|required', array('required' => 'Nomor Registrasi tidak ditemukan'));
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $no_registrasi = ($this->input->post('no_registrasi_hidden'))?$this->regex->_genRegex($this->input->post('no_registrasi_hidden'),'RGXINT'):0;

            // update riwayat pasien
            $riwayat_diagnosa = array(
                'diagnosa_awal' => $this->input->post('pl_diagnosa'),
                'anamnesa' => $this->input->post('pl_anamnesa'),
                'pengobatan' => $this->input->post('pl_pengobatan'),
                'pemeriksaan' => $this->input->post('pl_pemeriksaan'),
                'diagnosa_akhir' => $this->input->post('pl_diagnosa'),
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                'tinggi_badan' => (float)$this->input->post('pl_tb'),
                'tekanan_darah' => (float)$this->input->post('pl_td'),
                'berat_badan' => (float)$this->input->post('pl_bb'),
                'suhu' => (float)$this->input->post('pl_suhu'),
                'nadi' => (float)$this->input->post('pl_nadi'),
            );

            $this->db->where(array('kode_riwayat' => $this->input->post('kode_riwayat') ))->update('th_riwayat_pasien', $riwayat_diagnosa );

            // upload dokumen medis tambahan
            /*insert dokumen adjusment*/
            if(isset($_FILES['pf_file'])){
                $this->upload_file->CsmdoUploadMultiple(array(
                    'name' => 'pf_file',
                    'path' => 'uploaded/casemix/log/',
                    'ref_id' => $no_registrasi,
                    'ref_table' => 'csm_dokumen_export',
                    'flag' => 'dokumen_export',
                ));
            }

            // update dokumen
            $type = $this->input->post('form_type');

            if($_POST['submit'] == 'update_dok_klaim'){
                /*created document name*/
                /*clean first data*/
                //$this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi));
                
                $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
                //echo '<pre>';print_r($createDocument);die;
                
                foreach ($createDocument as $k_cd => $v_cd) {
                    # code...
                    $explode = explode('-', $v_cd);
                    /*explode result*/
                    $named = str_replace('BILL','',$explode[0]);
                    $no_mr = $explode[1];
                    $exp_no_registrasi = $explode[2];
                    $unique_code = $explode[3];

                    /*create and save download file pdf*/
                    if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
                    /*save document to database*/
                    /*csm_reg_pasien*/
                    $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
                    
                    $doc_save = array(
                        'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                        'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                        'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                        'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/log/'.$filename.'', 'RGXQSL'),
                    );
                    $doc_save['created_date'] = date('Y-m-d H:i:s');
                    $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                    /*check if exist*/
                    if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
                        $this->db->insert('csm_dokumen_export', $doc_save);
                    }
                    endif;
                    /*insert database*/
                }

            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.''));
            }
            
        }
    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
