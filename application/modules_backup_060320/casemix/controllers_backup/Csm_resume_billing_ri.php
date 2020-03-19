<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once (dirname(__FILE__) . "/Csm_billing_pasien.php");

class Csm_resume_billing_ri extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_resume_billing_ri');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        $this->load->model('Csm_resume_billing_ri_model', 'Csm_resume_billing_ri');
        /*enable profiler*/
        $this->output->enable_profiler(false);


    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Resume Billing',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_resume_billing_ri/index', $data);
    }

    
    public function get_data()
    {
        /*get data from model*/
            $list = $this->Csm_resume_billing_ri->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $dataRI = $this->Csm_billing_pasien->getDataRI($row_list->no_registrasi);
                $no++;
                $row = array();
                $link = 'casemix/Csm_billing_pasien';
                $kode_bag = $row_list->csm_rp_kode_bagian;
                $str_kode_bag = substr((string)$kode_bag, 0,2);
                $str_type = ($str_kode_bag=='01')?'RJ':'RI';
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = $row_list->no_registrasi;
                $row[] = $str_type;
                $row[] = '';
                $row[] = '<a href="#" onclick="getMenu('."'".$link.'/editBilling/'.$row_list->no_registrasi.''."/".$str_type."'".')">'.$row_list->csm_rp_no_sep.'</a>';
                $row[] = $row_list->csm_rp_no_mr;
                $row[] = strtoupper($row_list->csm_rp_nama_pasien);
                $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_masuk);
                $row[] = '<i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_keluar);
                $row[] = $row_list->csm_rp_nama_dokter;
                $row[] = $dataRI->nama_bagian;
                $row[] = $dataRI->nama_klas;
                
                $row[] = '';
                       
                $data[] = $row;
            }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Csm_resume_billing_ri->count_all(),
                        "recordsFiltered" => $this->Csm_resume_billing_ri->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);die;
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Csm_resume_billing_ri->count_all(),
                        //"recordsFiltered" => $this->registrasi_adm->count_filtered_data(),
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function getDetail($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = $this->Csm_resume_billing_ri->getByNoRegistrasi($no_registrasi);
        $html = $this->Csm_resume_billing_ri->getResumeBillingHtml($data, $no_registrasi);

        echo json_encode(array('html' => $html));
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Csm_resume_billing_ri->get_data();
        foreach ($list as $key => $value) {
            /*get detail*/
            $value->detail = $this->Csm_resume_billing_ri->getByNoRegistrasi($value->no_registrasi)->result();
            $getData[] = $value; 
        }
        //echo '<pre>';print_r($getData);die;
        //output to json format
        return $getData;
    }

    public function html_content($data)
    {
        /*get data from model*/
        $html = ' <table border="0">
                        <thead>
                          <tr>  
                            <th>No</th>
                            <th>No. Reg</th>
                            <th>No. SEP</th>
                            <th>No. MR</th>
                            <th width="150px">Nama Pasien</th>
                            <th width="120px">Tanggal Masuk</th>
                            <th width="120px">Tanggal Keluar</th>
                            <th>Dokter</th>
                            <th width="120px">Klinik</th>
                          </tr>
                        </thead>
                        <tbody>';
        $no = 0;
        foreach ($data as $key => $value) { $no++;

            $html .= '<tr>  
                        <th>'.$no.'</th>
                        <th>'.$value->no_registrasi.'</th>
                        <th>'.$value->csm_rp_no_sep.'</th>
                        <th>'.$value->csm_rp_no_mr.'</th>
                        <th>'.$value->csm_rp_nama_pasien.'</th>
                        <th>'.$value->csm_rp_tgl_masuk.'</th>
                        <th>'.$value->csm_rp_tgl_keluar.'</th>
                        <th>'.$value->csm_rp_nama_dokter.'</th>
                        <th>'.$value->csm_rp_nama_bagian.'</th>
                      </tr>';
                        $html .= '<tr>';
                        $html .= '<td>&nbsp;</td>';
                        foreach ($value->detail as $k => $v) {
                          $html .= '<td>'.$v->csm_rbp_ri_title.'</td>';
                        }
                        $html .= '</tr>';
                        $html .= '<tr>';
                        $html .= '<td>&nbsp;</td>';
                        foreach ($value->detail as $k2 => $v2) {
                           $html .= '<td>'.$v2->csm_rbp_ri_total.'</td>';
                        }
                        $html .= '</tr>';
        }
        $html .= '</tbody>
                  </table>';

        //output to json format
        return $html;
    }



}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
