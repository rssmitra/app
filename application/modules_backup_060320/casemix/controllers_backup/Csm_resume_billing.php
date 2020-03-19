<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_resume_billing extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_resume_billing');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_resume_billing_model', 'Csm_resume_billing');
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
        $this->load->view('Csm_resume_billing/index', $data);
    }

    
    public function get_data()
    {

        //print_r($_GET);die;
        /*get data from model*/
            $list = $this->Csm_resume_billing->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="left"><a href="#" onclick="getMenu('."'".'casemix/csm_billing_pasien/editBilling/'.$row_list->no_registrasi.''."/RJ'".')">'.$row_list->csm_rp_no_sep.'</a></div>';
                $row[] = $row_list->csm_rp_no_mr;
                $row[] = strtoupper($row_list->csm_rp_nama_pasien);
                $row[] = $this->tanggal->formatDate($row_list->csm_rp_tgl_keluar);
                $row[] = '<div align="right">'.number_format($row_list->csm_brp_bill_dr).'</div>';
                $row[] = '<div align="right">'.number_format($row_list->csm_brp_bill_adm).'</div>';
                $row[] = '<div align="right">'.number_format($row_list->csm_brp_bill_far).'</div>';
                $row[] = '<div align="right">'.number_format($row_list->csm_brp_bill_pm).'</div>';
                $row[] = '<div align="right">'.number_format($row_list->csm_brp_bill_tindakan).'</div>';
                $total = $row_list->csm_brp_bill_dr + $row_list->csm_brp_bill_adm + $row_list->csm_brp_bill_far + $row_list->csm_brp_bill_pm + $row_list->csm_brp_bill_tindakan;
                $row[] = '<div align="right">'.number_format($total).'</div>';
                
                $row[] = '';
                       
                $data[] = $row;
            }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Csm_resume_billing->count_all(),
                        "recordsFiltered" => $this->Csm_resume_billing->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);die;
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        //print_r($_POST);die;
        $output = array(
                        "recordsTotal" => $this->Csm_resume_billing->count_all(),
                        //"recordsFiltered" => $this->registrasi_adm->count_filtered_data(),
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Csm_resume_billing->get_data();
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
                            <th width="70px" align="center">Dokter</th>
                            <th width="70px" align="center">Administrasi</th>
                            <th width="70px" align="center">Obat/Farmasi</th>
                            <th width="70px" align="center">Penunjang Medis</th>
                            <th width="70px" align="center">Tindakan</th>
                            <th width="70px" align="center">Total</th>
                          </tr>
                        </thead>
                        <tbody>';
        $no = 0;
        foreach ($data as $key => $value) { $no++;
            /*$str_kode_bag = substr((string)$value->csm_rp_kode_bagian, 0,2);
            $str_type = ($str_kode_bag=='01')?'RJ':'RI';*/
            $total = $value->csm_brp_bill_dr + $value->csm_brp_bill_adm + $value->csm_brp_bill_far + $value->csm_brp_bill_pm + $value->csm_brp_bill_tindakan;

            $html .= '<tr>  
                        <td width="30px" align="center">'.$no.'</td>
                        <td>'.$value->csm_rp_no_sep.'</td>
                        <td>'.$value->csm_rp_no_mr.'</td>
                        <td width="150px">'.$value->csm_rp_nama_pasien.'</td>
                        <td width="150px">'.$value->csm_rp_tgl_masuk.'<br>'.$value->csm_rp_tgl_keluar.'</td>
                        <td width="70px" align="right">'.$value->csm_brp_bill_dr.'</td>
                        <td width="70px" align="right">'.$value->csm_brp_bill_adm.'</td>
                        <td width="70px" align="right">'.$value->csm_brp_bill_far.'</td>
                        <td width="70px" align="right">'.$value->csm_brp_bill_pm.'</td>
                        <td width="70px" align="right">'.$value->csm_brp_bill_tindakan.'</td>
                        <td width="70px" align="right">'.$total.'</td>
                      </tr>';
        }
        $html .= '</tbody>
                  </table>';

        //output to json format
        return $html;
    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
