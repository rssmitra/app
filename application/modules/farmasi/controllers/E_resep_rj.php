<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class E_resep_rj extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/E_resep_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('E_resep_rj_model', 'E_resep_rj');
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('pelayanan/Pl_pelayanan_model', 'Pl_pelayanan');

        $this->load->module('farmasi/E_resep');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        // include qr lib
        $this->load->library('qr_code_lib');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'RJ'
        );
        /*load view index*/
        $this->load->view('E_resep_rj/index', $data);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $detail_log = $this->Dokumen_klaim_prb->get_detail($id);
        $data['resep'] = $detail_log;
        // get dokumen klaim
        $data['dokumen'] = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('kode_trans_far' => $id))->result();
        $month = date("M",strtotime($data['value']->tgl_trans));
        $year = date("Y",strtotime($data['value']->tgl_trans));
        $data['path_dok_klaim'] = PATH_DOK_KLAIM_FARMASI.'merge-'.$month.'-'.$year.'/'.$data['value']->no_sep.'.pdf';
        // echo '<pre>';print_r($data);
        $temp_view = $this->load->view('farmasi/Dokumen_klaim_prb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->E_resep_rj->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center" width="30px">'.$no.'</div>';
            $row[] = '';
            $row[] = $row_list->kode_pesan_resep;
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=RJ'".')" class="label label-primary">'.$row_list->kode_pesan_resep.'</a></div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_pesan).'</div>';
            $jenis_resep = ($row_list->jenis_resep == 'prb')?'<span class="red">PRB</span>':'<span class="green">NON PRB</span>';
            $row[] = '<div class="center"><b>'.$jenis_resep.'</b></div>';
            $row[] = '<div class="center"><b>'.$row_list->no_mr.'</b></div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->nama_pegawai;
            $row[] = ucwords($row_list->nama_bagian);
            $penjamin = (!empty($row_list->nama_perusahaan))?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $no_sep = ($row_list->kode_perusahaan == 120) ? '<br>('.$row_list->no_sep.')' : '';
            $row[] = ucwords($penjamin).$no_sep;
            $row[] = $row_list->diagnosa_akhir;
            $status_tebus = ($row_list->status_tebus ==  1)?'<label class="label label-xs label-success">Selesai</label>':'<label class="label label-xs label-warning">Belum diproses</label>';
            $verifikasi_apotik_online = ($row_list->verifikasi_apotik_online ==  1)?'checked':'';
            $row[] = '<div class="center"><label>
                                            <input name="switch-field-1" class="ace ace-switch" id="status_verif_'.$row_list->kode_pesan_resep.'" onchange="udpateStatusVerif('.$row_list->kode_pesan_resep.')" type="checkbox" value="1" '.$verifikasi_apotik_online.'>
                                            <span class="lbl"></span>
                                        </label></div>';
            $row[] = '<div class="center">'.$status_tebus.'</div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->E_resep_rj->count_all(),
                        "recordsFiltered" => $this->E_resep_rj->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function getAntrianResep()
    {
        /*get data from model*/
        $list = $this->E_resep_rj->get_data();
        //output to json format
        echo json_encode($list);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'E_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $tipe_layanan = strtoupper($_GET['tipe_layanan']);
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->E_resep_rj->get_by_id($id);
        $data['trans_farmasi'] = $this->E_resep_rj->get_trans_farmasi($id);
        
        $data['riwayat_penunjang'] = $this->get_riwayat_penunjang($_GET['mr']);
        // echo '<pre>';print_r($data['riwayat_penunjang']);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['tipe_layanan'] = $tipe_layanan;
        $data['str_tipe_layanan'] = ($tipe_layanan=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('E_resep_rj/form', $data);
    }

    public function frm_telaah_resep($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'E_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $tipe_layanan = strtoupper($_GET['tipe_layanan']);
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->E_resep_rj->get_by_id($id);
        $data['trans_farmasi'] = $this->E_resep_rj->get_trans_farmasi($id);
        $eresep = new E_resep;
        $data['resep_cart'] = $eresep->get_cart_resep($data['value']->no_kunjungan);
        
        $data['riwayat_penunjang'] = $this->get_riwayat_penunjang($_GET['mr']);
        // echo '<pre>';print_r($data['riwayat_penunjang']);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['tipe_layanan'] = $tipe_layanan;
        $data['str_tipe_layanan'] = ($tipe_layanan=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('E_resep_rj/form_telaah_resep', $data);
    }

    public function copy_resep($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $data = [];
        $list_resep = $this->E_resep->get_cart_resep($id);
        $data['eresep'] = $list_resep;
        $data['kode_pesan_resep'] = $id;
        // echo "<pre>"; print_r($data);die;
        /*load form view*/
        $this->load->view('E_resep_rj/form_cr_eresep', $data);
    }

    public function preview_copy_resep($id){
        $data = [];
        $result = $this->db->select('nama_pasien, tgl_pesan, nama_pegawai as nama_dokter, copy_resep_text, kode_pesan_resep, fr_tc_pesan_resep.*')->join('mt_master_pasien', 'mt_master_pasien.no_mr=fr_tc_pesan_resep.no_mr','left')->join('mt_karyawan', 'mt_karyawan.kode_dokter=fr_tc_pesan_resep.kode_dokter','left')->get_where('fr_tc_pesan_resep', ['kode_pesan_resep' => $id])->row();
        $data['result'] = $result;
        // echo "<pre>"; print_r($data); die;
        // qrcode
        $config = [
            'no_registrasi' => $result->no_registrasi,
            'kode' => $result->kode_pesan_resep,
            'tanggal' => $result->tgl_pesan,
            'flag' => 'COPY_RESEP',
        ];
        $qr_url = $this->qr_code_lib->qr_url($config);
        $img = $this->qr_code_lib->generate($qr_url);

        // echo "<pre>"; print_r($data);die;
        $data['kode_pesan_resep'] = $id;
        $data['img_qr'] = $img;
        $data['url_qr'] = $qr_url;
        $this->load->view('E_resep_rj/preview_copy_resep', $data);
    }

    public function process_copy_resep()
    {
        
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('content', 'Tulis Resep', 'trim|required');
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            
             /*update existing*/
             $data_farmasi['copy_resep_text'] = $_POST['content'];
             $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
             $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
             $this->db->update('fr_tc_pesan_resep', $data_farmasi, array('kode_pesan_resep' => $_POST['kode_pesan_resep']) );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_pesan_resep' => $_POST['kode_pesan_resep']));
            }
        
        }

    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
