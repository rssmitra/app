<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_tagihan_perusahaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/penagihan/Adm_tagihan_perusahaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/penagihan/Adm_tagihan_perusahaan_model', 'Adm_tagihan_perusahaan');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('penagihan/Adm_tagihan_perusahaan/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Create Invoice '.strtolower($this->title).'', 'Adm_tagihan_perusahaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_tagihan_perusahaan->get_by_id($id); 
        
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['no_invoice'] = $this->master->format_no_invoice($_GET['jenis_pelayanan']);
        $data['detail_pasien'] = $this->Adm_tagihan_perusahaan->get_detail_pasien($id); 
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penagihan/Adm_tagihan_perusahaan/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = ($_GET) ? $this->Adm_tagihan_perusahaan->get_datatables() : [] ;
        // $qry_url = ($_GET) ? '?keyword='.$_GET['keyword'].'&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'&jenis_pelayanan='.$_GET['jenis_pelayanan'].'' : '' ;
        $qry_url = ($_GET) ? '?'.http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $jumlah_tagihan = $row_list->jml_tghn;
            $no++;
            $row = array();
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->kode_perusahaan;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->nama_perusahaan;
            $row[] = '<div class="pull-right"><a href="#">'.number_format($jumlah_tagihan).',-</a></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'adm_pasien/penagihan/Adm_tagihan_perusahaan/form/".$row_list->kode_perusahaan.$qry_url."'".')" class="label label-xs label-primary">Buat Invoice</a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_tagihan_perusahaan->count_all(),
                        "recordsFiltered" => $this->Adm_tagihan_perusahaan->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_hist_inv($kode_perusahaan)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_perusahaan->get_hist_inv($kode_perusahaan);
        $data = array(
            'kode_perusahaan' => $kode_perusahaan,
            'result' => $list,
        ); 
        $html = $this->load->view('penagihan/Adm_tagihan_perusahaan/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_perusahaan->get_invoice_detail($id_tagih);
        $no_invoice = $list[0]->no_invoice_tagih;
        echo json_encode(array('data' => $list, 'no_invoice' => $no_invoice));
    }

    public function process()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('no_invoice', 'No Invoice', 'trim|required');
        $val->set_rules('tgl_tagihan', 'Tanggal Faktur', 'trim|required');
        $val->set_rules('tgl_jatuh_tempo', 'Tanggal Jatuh Tempo', 'trim|required');
        $val->set_rules('diskon', 'Diskon', 'trim|required');
        $val->set_rules('is_checked[]', 'Is Checked', 'trim|required', array('required' => 'Silahkan ceklist terlebih dahulu!'));
        
        
        $val->set_message('required', "Silahkan isi field \"%s\"");
        
        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            // print_r($_POST);die;
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            
            // data header tagihan
            $dataexc = array(
                'no_invoice_tagih' => $this->regex->_genRegex($_POST['no_invoice'],'RGXQSL'),
                'jenis_tagih' => $this->regex->_genRegex(3,'RGXINT'),
                'tgl_tagih' => $this->regex->_genRegex($_POST['tgl_tagihan'],'RGXQSL'),
                'jumlah_tagih' => $this->regex->_genRegex($_POST['total_tagihan'],'RGXINT'),
                'diskon' => $this->regex->_genRegex($_POST['diskon'],'RGXINT'),
                'nama_tertagih' => $this->regex->_genRegex($_POST['nama_perusahaan'],'RGXQSL'),
                'id_tertagih' => $this->regex->_genRegex($_POST['kode_perusahaan'],'RGXINT'),
                'id_dd_user' => $this->regex->_genRegex($this->session->userdata('user')->user_id, 'RGXQSL'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'tgl_jt_tempo' => $this->regex->_genRegex($_POST['tgl_jatuh_tempo'],'RGXQSL'),
                'tr_yg_diskon' => $this->regex->_genRegex($_POST['total_diskon_val'],'RGXINT'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname, 'RGXQSL');
                $newId = $this->Adm_tagihan_perusahaan->save('tc_tagih', $dataexc);
                /*save logs*/
                $this->logs->save('tc_tagih', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_tagih');
                // print_r($_POST);die;
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname);
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Adm_tagihan_perusahaan->update('tc_tagih', array('id_tc_tagih' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_tagih', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_tagih');
            }

            if(isset($_POST['is_checked'])){
                $this->db->delete('tc_tagih_det', array('id_tc_tagih' => $newId) );
                foreach ($_POST['is_checked'] as $key => $value) {
                    $data_detail[] = array(
                        'kode_tc_trans_kasir' => $value,
                        'id_tc_tagih' => $newId,
                        'tgl_kui' => $_POST['tgl_tagihan'].' '.date('H:i:s'),
                        'no_mr' => $_POST['no_mr'][$value],
                        'no_registrasi' => $_POST['no_registrasi'][$value],
                        'nama_pasien' => $_POST['nama_pasien'][$value],
                        'kode_perusahaan' => $_POST['kode_perusahaan'],
                        'jumlah_billing' => $_POST['jumlah_billing'][$value],
                        'jumlah_dijamin' => $_POST['jumlah_ditagih'][$value],
                        'jumlah_tagih' => $_POST['beban_pasien'][$value],
                        'penyesuaian' => $_POST['input_penyesuaian_'.$value.''],
                    );
                    $data_update[] = array(
                        'kode_tc_trans_kasir' => $value,
                        'kd_inv_persh_tx' => $newId,
                    );
                    // Update tc_trans_kasir -> kd_inv_persh_tx
                    // $this->db->where('kode_tc_trans_kasir', $value);
                    // $this->db->update('tc_trans_kasir', ['kd_inv_persh_tx' => $newId, 'nama_pasien' => $_POST['nama_pasien'][$value]]);
                }
                $this->db->insert_batch('tc_tagih_det', $data_detail);
                $this->db->update_batch('tc_trans_kasir', $data_update, 'kode_tc_trans_kasir');
            }
            
            // mapping jurnal
            // $this->accounting->create_jurnal_piutang($dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $newId));
            }
        }
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
