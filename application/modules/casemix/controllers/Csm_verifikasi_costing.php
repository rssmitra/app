<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_verifikasi_costing extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_verifikasi_costing');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_verifikasi_costing_model', 'Csm_verifikasi_costing');
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        $this->base_file = $_SERVER['DOCUMENT_ROOT'].'/sirs/app';


    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Verifikasi Costing',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_verifikasi_costing/index', $data);
    }

    public function editBilling($no_registrasi, $tipe)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Csm_verifikasi_costing/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        /*load form view*/
        $view_name = ($tipe=='RJ')?'form_edit':'form_edit_ri';
        $title_name = ($tipe=='RJ')?'Rawat Jalan':'Rawat Inap';
        $data['no_registrasi'] = $no_registrasi;
        $data['form_type'] = $tipe;
        $data['value'] = $this->Csm_billing_pasien->get_by_id($no_registrasi);
        $data['title'] = 'Verifikasi Costing '.$title_name.'';
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

        // lampiran pengkajian pasien
        $file_pengkajian = $this->db->get_where('view_cppt', array('view_cppt.no_mr' => $data['value']->no_mr, 'jenis_form !=' => 0, 'no_registrasi' => $no_registrasi))->result();
        $data['file_pengkajian'] = $file_pengkajian;

        // echo '<pre>';print_r($data);die;
        $this->load->view('Csm_verifikasi_costing/'.$view_name.'', $data);
    }

    public function viewDetailDokumen($no_registrasi, $tipe)
    {
        $data = array();
        $value = $this->db->get_where('csm_reg_pasien', array('no_registrasi' => $no_registrasi))->row();
        $html = '';
        $html .= '<div style="padding-left: 38px; padding: 10px">';
        $html .= '<span style="padding-top: 10px; font-weight: bold; font-size: 14px">Log Dokumen</span>';
        $html .= $this->upload_file->CsmgetUploadedFile($no_registrasi);
        $html .= '<input type="hidden" id="tgl_masuk_'.$no_registrasi.'" value="'.$value->csm_rp_tgl_masuk.'">';
        $html .= '<input type="hidden" id="tgl_keluar_'.$no_registrasi.'" value="'.$value->csm_rp_tgl_keluar.'">';
        $html .= '<input type="hidden" id="csm_rp_no_sep_'.$no_registrasi.'" value="'.$value->csm_rp_no_sep.'">';
        $html .= '<div class="center"><button onclick="updateDokumen('.$no_registrasi.', '."'".$tipe."'".')" type="button" name="submit" class="btn btn-sm btn-primary" value="update_dok_klaim">
        <i class="ace-icon fa fa-files-o icon-on-right bigger-110"></i>
        Update Dokumen Klaim
      </button><a href="'.base_url().'/casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$tipe.'" target="_blank"  class="btn btn-sm btn-danger">
      <i class="ace-icon fa fa-pdf-file icon-on-right bigger-110"></i>
      Merge PDF Files
    </a></div>';
        $html .= '</div>';
        //echo '<pre>';print_r($data);die;
        echo json_encode(array('html' => $html));
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Csm_verifikasi_costing->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->no_registrasi;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="left"><a href="#" onclick="getMenu('."'".'casemix/Csm_verifikasi_costing/editBilling/'.$row_list->no_registrasi.''."/".$row_list->csm_rp_tipe."'".')" style="font-weight: bold; color: blue">'.$row_list->csm_rp_no_mr.'</a><br>'.strtoupper($row_list->csm_rp_nama_pasien).'</div>';
            $sep = (strlen($row_list->csm_rp_no_sep) > 18) ? $row_list->csm_rp_no_sep : '<span style="color: red; font-weight: bold">Belum ada SEP</span>';
            $row[] = $sep;
            $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDateDmy($row_list->csm_rp_tgl_masuk).'<br>'.'<i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDateDmy($row_list->csm_rp_tgl_keluar);

            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->created_date).'<br>['.$row_list->created_by.']</div>';
            $row[] = strtoupper($row_list->csm_rp_bagian).'<br>'.$row_list->csm_rp_nama_dokter;
            $row[] = $row_list->csm_rp_tipe;

            if(file_exists($this->base_file.'/'.$row_list->csm_dk_fullpath)){
                $file_exist = '<span><a style="color: blue !important; font-weight: bold" href="'.$row_list->csm_dk_base_url.$row_list->csm_dk_fullpath.'" target="_blank">View File</a></span>';
            }else{
                $file_exist = '<span style="color: red; font-weight: bold">No File</span>';
            }
            $row[] = '<div class="center">'.$file_exist.'</div>';
            // $row[] = '<div class="center">'.$row_list->csm_dk_base_url.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->csm_dk_total_klaim).'</div>';
            $data[] = $row;
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Csm_verifikasi_costing->count_all(),
                    "recordsFiltered" => $this->Csm_verifikasi_costing->count_filtered(),
                    "data" => $data,
        );
    
        
        //output to json format
        echo json_encode($output);
    }

    public function export_excel()
    {
        /*get data from model*/
        $list = $this->Csm_verifikasi_costing->get_data();
        $data['result'] = $list;
        $this->load->view('Csm_verifikasi_costing/export_excel_view', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_content_data()
    {
        /*get data from model*/
        $list = $this->Csm_verifikasi_costing->get_data();
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
