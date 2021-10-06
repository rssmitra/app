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
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
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

    public function editBilling($no_registrasi, $tipe)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Rm_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        /*load form view*/
        $view_name = ($tipe=='RJ')?'form_edit':'form_edit_ri';
        $title_name = ($tipe=='RJ')?'Rawat Jalan':'Rawat Inap';
        $data['no_registrasi'] = $no_registrasi;
        $data['form_type'] = $tipe;
        $data['value'] = $this->Csm_billing_pasien->get_by_id($no_registrasi);
        $data['title'] = 'Resume Medis Pasien '.$title_name.'';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['attachment'] = $this->upload_file->CsmgetUploadedFile($no_registrasi);
        //echo '<pre>';print_r($data);die;
        /*get data trans pelayanan by no registrasi from sirs*/
        $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        //echo '<pre>';print_r($sirs_data);die;
        /*cek apakah data sudah pernah diinsert ke database atau blm*/
        if( $this->Csm_billing_pasien->checkExistingData($no_registrasi) ){
            
        }else{
        /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
            /*insert data untuk pertama kali*/
            if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

        }

    //    echo '<pre>';print_r($data);die;
        /*no action if data exist, continue to view data*/
        $dataBilling = $this->Csm_billing_pasien->getBillingDataLocal($no_registrasi, $tipe);
        $data['reg'] = (count($dataBilling['reg_data']) > 0) ? $dataBilling['reg_data'] : [] ;
        if( $tipe=='RJ' ){
            $group = array();
            foreach ($dataBilling['billing'] as $value) {
                /*group berdasarkan nama jenis tindakan*/
                $group[$value->csm_bp_nama_jenis_tindakan][] = $value;
            }
            $data['group'] = $group;
            $data['resume'] = $dataBilling['resume'];
        }else{
            $data['content_view'] = $this->Csm_billing_pasien->getDetailBillingRI($no_registrasi, $tipe, $sirs_data);
        }

        //echo '<pre>';print_r($data);die;
        $this->load->view('Rm_pasien/'.$view_name.'', $data);
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
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="left"><a href="#" onclick="getMenu('."'".'rekam_medis/Rm_pasien/editBilling/'.$row_list->no_registrasi.''."/".$row_list->csm_rp_tipe."'".')">'.$row_list->no_registrasi.'</a></div>';
            $row[] = $row_list->csm_rp_no_sep;
            $row[] = $row_list->csm_rp_no_mr;
            $row[] = strtoupper($row_list->csm_rp_nama_pasien);
            $row[] = strtoupper($row_list->csm_rp_bagian);
            $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_masuk);
            $row[] = '<i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->csm_rp_tgl_keluar);

            $row[] = '<div class="center">'.$row_list->csm_rp_tipe.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->csm_dk_total_klaim).'</div>';
            $row[] = $this->tanggal->formatDate($row_list->created_date).'<br>by : '.$row_list->created_by;
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


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
